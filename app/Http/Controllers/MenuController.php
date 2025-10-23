<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
    
        // Filter berdasarkan kategori (jika ada)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
    
        // Fitur pencarian (jika ada)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        $products = $query->paginate(10); // Pakai pagination biar rapi
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
    
        return view('menu', compact('products', 'categories'));
    }

    public function landing(Request $request)
    {
        $user = Auth::user();
        $query = Product::with('category');
    
        if ($user) {
            // --- Rekomendasi berdasarkan pembelian user ---
            $purchasedProductNames = OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->pluck('produk')->unique();
    
            if ($purchasedProductNames->isNotEmpty()) {
                $purchasedCategories = Product::whereIn('name', $purchasedProductNames)
                    ->pluck('category_id')
                    ->unique();
    
                $query->whereIn('category_id', $purchasedCategories);
            }
    
            // Batasi 5 produk rekomendasi
            $products = $query->limit(4)->get();
        } else {
            // --- Produk Best Seller (tanpa login) ---
            // Hitung jumlah penjualan tiap produk
            $bestSellerIds = OrderItem::selectRaw('produk, SUM(quantity) as total_sold')
                ->groupBy('produk')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->pluck('produk'); // ambil nama produk paling laku
    
            // Ambil produk yang sesuai urutan best seller
            $products = Product::with('category')
                ->whereIn('name', $bestSellerIds)
                ->orderByRaw("FIELD(name, '" . $bestSellerIds->implode("','") . "')")
                ->get();
        }
    
        $categories = Category::all();
    
        return view('landing', compact('products', 'categories'));
    }


  public function layananOngkir(Request $request)
{
    // Baca file JSON wilayah dari storage
    $path = storage_path('app/data/wilayah_kuningan.json');
    if (!file_exists($path)) {
        abort(404, 'File wilayah tidak ditemukan.');
    }

    $json = file_get_contents($path);
    $wilayah = json_decode($json, true);

    if (!$wilayah) {
        abort(400, 'Data wilayah tidak valid.');
    }

    // Ubah struktur data JSON menjadi array datar (flat)
    $data = [];
    foreach ($wilayah as $kabupaten => $kecamatans) {
        foreach ($kecamatans as $kecamatan => $desas) {
            foreach ($desas as $desa) {
                $data[] = [
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $kecamatan,
                    'desa' => $desa,
                    'tarif_ongkir' => rand(10000, 15000),
                ];
            }
        }
    }

    $collection = collect($data);

    // Fitur pencarian
    $search = $request->input('search');
    if ($search) {
        $collection = $collection->filter(function ($item) use ($search) {
            return stripos($item['kabupaten'], $search) !== false ||
                   stripos($item['kecamatan'], $search) !== false ||
                   stripos($item['desa'], $search) !== false;
        });
    }

    // Pagination manual (10 data per halaman)
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginated = new LengthAwarePaginator(
        $pagedData,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('menu.layanan_ongkir', ['dataOngkir' => $paginated]);
}


    
    
}
