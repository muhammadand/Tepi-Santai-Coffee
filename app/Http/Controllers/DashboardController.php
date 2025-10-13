<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
class DashboardController extends Controller
{
   

    public function laporan(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
    
        // Query dasar untuk order_items
        $queryOrderItems = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id');
    
        // Filter tanggal kalau dipilih
        if ($startDate && $endDate) {
            $queryOrderItems->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);
        } else {
            // Default: tahun ini
            $queryOrderItems->whereYear('orders.created_at', date('Y'));
        }
    
        // 🔹 Data laporan tabel (per transaksi / per produk)
        $laporanPenjualan = (clone $queryOrderItems)
            ->selectRaw('DATE(orders.created_at) as tanggal, order_items.produk, SUM(order_items.quantity) as jumlah, SUM(order_items.quantity * order_items.price) as total')
            ->groupBy('tanggal', 'produk')
            ->orderBy('tanggal', 'asc')
            ->get();
    
        // 🔹 Data grafik penjualan per bulan
        $penjualanPerBulan = (clone $queryOrderItems)
            ->selectRaw('MONTH(orders.created_at) as bulan, SUM(order_items.quantity * order_items.price) as total_penjualan')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total_penjualan', 'bulan')
            ->map(fn($v) => (int)$v);
    
        // 🔹 Produk terlaris
        $topProduk = (clone $queryOrderItems)
            ->select('order_items.produk', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->groupBy('order_items.produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();
    
        // 🔹 Kategori favorit
        $kategoriFavorit = DB::table('order_items')
            ->join('products', 'order_items.produk', '=', 'products.name')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as kategori', DB::raw('SUM(order_items.quantity) as total'))
            ->whereYear('order_items.created_at', date('Y'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();
    
        // 🔹 Pelanggan paling sering transaksi (TOP 5)
        $topPelanggan = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(orders.id) as total_transaksi'))
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);
            }, function ($query) {
                $query->whereYear('orders.created_at', date('Y'));
            })
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_transaksi')
            ->limit(5)
            ->get();
    
        // 🔹 Efektivitas promo
        $efektivitasPromo = DB::table('order_items')
            ->join('products', 'order_items.produk', '=', 'products.name')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_terjual'), 'products.discount')
            ->where('products.discount_active', true)
            ->whereYear('order_items.created_at', date('Y'))
            ->groupBy('products.name', 'products.discount')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();
    
        // 🔹 Kirim ke view
        return view('admin.laporan', compact(
            'penjualanPerBulan',
            'topProduk',
            'kategoriFavorit',
            'efektivitasPromo',
            'laporanPenjualan',
            'topPelanggan', // ← ditambahkan
            'startDate',
            'endDate'
        ));
    }
    
    
    
    
    
public function exportExcel(Request $request)
{
    $range = $request->range;
    $date = $request->date;
    $orders = $this->getFilteredOrders($range, $date);
    return Excel::download(new OrderExport($orders), 'laporan-orders.xlsx');
}

public function exportPdf(Request $request)
{
    $range = $request->range;
    $date = $request->date;
    $orders = $this->getFilteredOrders($range, $date);
    
    $pdf = PDF::loadView('admin.laporan.export_pdf', compact('orders', 'range', 'date'));
    return $pdf->download('laporan-orders.pdf');
}

private function getFilteredOrders($range, $date)
{
    $query = Order::where('status_order', 'diterima');

    if ($range === 'hari') {
        $query->whereDate('created_at', Carbon::parse($date));
    } elseif ($range === 'minggu') {
        $query->whereBetween('created_at', [
            Carbon::parse($date)->startOfWeek(),
            Carbon::parse($date)->endOfWeek()
        ]);
    } elseif ($range === 'bulan') {
        $query->whereYear('created_at', Carbon::parse($date)->year)
              ->whereMonth('created_at', Carbon::parse($date)->month);
    }

    return $query->get();
}

    
public function index(Request $request)
{
    $year = $request->get('year', date('Y')); // default tahun ini

    // Statistik dasar
    $totalUsers = User::count();
    $totalProducts = Product::count();
    $totalOrders = Order::whereYear('created_at', $year)->count();
    $pendingOrders = Order::where('status_order', 'dipesan')->whereYear('created_at', $year)->count();

    // Penjualan per bulan
    $penjualanPerBulan = Order::select(
        DB::raw('MONTH(orders.created_at) as bulan'),
        DB::raw('SUM(order_items.quantity * order_items.price) as total')
    )
    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
    ->whereYear('orders.created_at', $year)
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->pluck('total', 'bulan');

    // Top 5 produk terlaris
    $topProduk = OrderItem::select(
        'produk',
        DB::raw('SUM(quantity) as total_terjual')
    )
    ->join('orders', 'orders.id', '=', 'order_items.order_id')
    ->whereYear('orders.created_at', $year)
    ->groupBy('produk')
    ->orderByDesc('total_terjual')
    ->limit(5)
    ->get();

    // Kategori paling diminati
    $kategoriFavorit = Product::select(
        'categories.name as kategori',
        DB::raw('SUM(order_items.quantity) as total')
    )
    ->join('categories', 'categories.id', '=', 'products.category_id')
    ->join('order_items', 'order_items.produk', '=', 'products.name')
    ->join('orders', 'orders.id', '=', 'order_items.order_id')
    ->whereYear('orders.created_at', $year)
    ->groupBy('categories.name')
    ->orderByDesc('total')
    ->get();

    // Pelanggan aktif per bulan
    $pelangganAktif = Order::select(
        DB::raw('MONTH(orders.created_at) as bulan'),
        DB::raw('COUNT(DISTINCT user_id) as total')
    )
    ->whereYear('orders.created_at', $year)
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->pluck('total', 'bulan');

    return view('admin.dashboard', compact(
        'totalUsers', 'totalProducts', 'totalOrders', 'pendingOrders',
        'penjualanPerBulan', 'topProduk', 'kategoriFavorit', 'pelangganAktif', 'year'
    ));
}

}
