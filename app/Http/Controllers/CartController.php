<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan isi keranjang
public function index()
{
    // ✅ Pastikan user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses keranjang.');
    }

    $user = Auth::user(); // ambil data user yang sedang login
    $cart = session()->get('cart', []);

    // ✅ Ambil order terakhir user
    $lastOrder = Order::where('user_id', $user->id)
        ->latest()
        ->first();

    // ✅ Ambil data kabupaten/kecamatan/desa dari JSON
    $kabupatenDataPath = storage_path('app/data/wilayah_kuningan.json');
    $kabupatenData = file_exists($kabupatenDataPath)
        ? json_decode(file_get_contents($kabupatenDataPath), true)
        : [];

    // ✅ Kirim semua data ke view
    return view('cart.index', [
        'cart' => $cart,
        'user' => $user,
        'kabupatenData' => $kabupatenData,
        'lastAlamat' => $lastOrder?->detail_alamat,
    ]);
}

    // Menambahkan produk ke keranjang
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);
    
        // Hitung harga setelah diskon
        $finalPrice = $product->discount_active ? ($product->price - $product->discount) : $product->price;
    
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $finalPrice,
                'discount' => $product->discount,
                'discount_active' => $product->discount_active,
                'quantity' => 1,
            ];
        }
    
        session()->put('cart', $cart);
    
        return response()->json(['cart' => $cart]);
    }
    
    

    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        if (!isset($cart[$productId])) {
            return response()->json(['error' => 'Item tidak ditemukan'], 404);
        }

        $change = $request->input('change', 0);
        $cart[$productId]['quantity'] += $change;

        if ($cart[$productId]['quantity'] <= 0) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        return response()->json(['cart' => $cart]);
    }
    // Menghapus item dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['cart' => $cart, 'message' => 'Produk dihapus dari keranjang']);
    }

    // Checkout dan simpan data pemesanan

}
