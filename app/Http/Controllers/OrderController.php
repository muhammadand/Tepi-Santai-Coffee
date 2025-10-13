<?php

namespace App\Http\Controllers;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
{
    $orders = Order::with('items')->get(); // Ambil semua order beserta item-nya
    return view('orders.index', compact('orders'));
}

public function myOrders()
{
    $orders = Order::with('items')
        ->where('user_id', Auth::id()) // hanya pesanan user yang login
        ->latest()
        ->get();

    return view('orders.my_orders', compact('orders'));
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status_order' => 'required|string',
    ]);

    $order = Order::findOrFail($id);
    $order->status_order = $request->status_order;
    $order->save();

    return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
}

public function updateStatus1($id, $status)
{
    $order = Order::findOrFail($id);
    $order->status_order = $status;
    $order->save();

    return redirect()->back()->with('success', "Status pesanan #{$order->id} berhasil diubah menjadi '{$status}'.");
}


public function bulkDelete(Request $request)
{
    $ids = $request->order_ids;
    if ($ids) {
        Order::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }
    return redirect()->back()->with('success', 'Tidak ada pesanan yang dipilih.');
}
public function edit($id)
{
    $order = Order::with('items')->findOrFail($id);
    return view('orders.edit', compact('order'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'status_order' => 'required|in:dipesan,diterima,diproses,selesai',
        'payment_status' => 'required|in:pending,paid,failed',
    ]);

    $order = Order::findOrFail($id);
    $order->status_order = $request->status_order;
    $order->payment_status = $request->payment_status;
    $order->save();

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil diperbarui.');
}
public function destroy($id)
{
    $order = Order::findOrFail($id);

    // Jika ada relasi order_items, hapus juga itemnya
    $order->items()->delete(); // jika relasi bernama "items"

    $order->delete();

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus.');
}




public function strukTunai(Order $order)
{
    return view('orders.struk_tunai', compact('order'));
}


    public function pay($id)
    {
        $order = Order::findOrFail($id);
        $midtrans_order_id = 'ORDER-' . $order->id . '-' . time();
        $order->update(['midtrans_order_id' => $midtrans_order_id]);
    
        // Hitung total manual
        $total = 0;
        foreach ($order->items as $item) {
            $total += $item->price * $item->quantity;
        }
    
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    
        $customer_details = [
            'first_name' => $order->nama ?? 'Customer',
            'email' => 'customer@example.com',  // ganti dengan data valid kalau ada
            'phone' => '081234567890',
        ];
    
        $transaction = [
            'transaction_details' => [
                'order_id' => $midtrans_order_id,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => $customer_details,
    
            // Tambah callback URL finish supaya redirect ke finish route
            'callbacks' => [
                'finish' => route('orders.finish', ['id' => $order->id]),
            ],
    
            // Atau bisa juga pakai finish_redirect_url
            'finish_redirect_url' => route('orders.finish', ['id' => $order->id]),
            'unfinish_redirect_url' => route('menu.index'),
            'error_redirect_url' => route('menu.index'),
        ];
    
        try {
            $snapTransaction = Snap::createTransaction($transaction);
            return redirect()->away($snapTransaction->redirect_url);
        } catch (\Exception $e) {
            Log::error('Midtrans Payment Error: ' . $e->getMessage());
            return redirect()->route('menu.index')->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }
    
    public function finish($id)
    {
        $order = Order::findOrFail($id);
        $transaction_status = request()->get('transaction_status');
        Log::info('Transaction Status: ' . $transaction_status);
    
        if (!$transaction_status) {
            Log::error('Status transaksi tidak ditemukan untuk order ID: ' . $id);
            return redirect()->route('menu.index')->with('error', 'Status transaksi tidak ditemukan.');
        }
    
        try {
            switch ($transaction_status) {
                case 'settlement':
                    $order->payment_status = 'paid';
                    break;
    
                case 'pending':
                    $order->payment_status = 'pending';
                    break;
    
                case 'failed':
                    $order->payment_status = 'failed';
                    break;
    
                default:
                    $order->payment_status = 'cancelled';
                    break;
            }
    
            $order->save();  // langsung simpan tanpa cek return
    
            Log::info('Status pembayaran berhasil diperbarui untuk order ID: ' . $id);
            return redirect()->route('orders.details', $id)->with('success', 'Pembayaran diproses dengan status: ' . $transaction_status);
    
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui status pembayaran untuk order ID: ' . $id . ' - ' . $e->getMessage());
            return redirect()->route('menu.index')->with('error', 'Terjadi kesalahan saat memperbarui status pembayaran.');
        }
    }
    
    
// app/Http/Controllers/OrderController.php

public function details($id)
{
    $order = Order::with('items')->findOrFail($id); // pastikan relasi items sudah dibuat
    return view('orders.details', compact('order'));
}

public function print($id)
{
    $order = Order::with('items')->findOrFail($id);
    return view('orders.print', compact('order'));
}




}
