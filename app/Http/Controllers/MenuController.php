<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    
    
}
