<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckoutController extends Controller
{
   public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'kabupaten' => 'required|string|max:255',
        'kecamatan' => 'required|string|max:255',
        'desa' => 'required|string|max:255',
        'detail_alamat' => 'required|string|max:255',
        'payment_method' => 'required|in:tunai,non-tunai',
        'ongkir' => 'required|numeric|min:0',
    ]);

    // Ambil data cart dari session
    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Keranjang belanja kosong.');
    }

    // Hitung subtotal dari cart
    $subtotal = collect($cart)->sum(function($item) {
        return $item['price'] * $item['quantity'];
    });

    // Total termasuk ongkir
    $total = $subtotal + $request->ongkir;
    

    // Simpan order ke database
    $order = Order::create([
        'user_id' => Auth::id(),
        'nama' => $request->nama,
        'kabupaten' => $request->kabupaten,
        'kecamatan' => $request->kecamatan,
        'desa' => $request->desa,
        'detail_alamat' => $request->detail_alamat,
        'ongkir' => $request->ongkir,
        'total' => $total,
        'status_order' => 'dipesan',
        'payment_method' => $request->payment_method,
    ]);

    // Simpan item order
    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'produk' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // Kosongkan cart
    session()->forget('cart');

    session()->flash('order_success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    // Redirect sesuai metode pembayaran
    if ($order->payment_method === 'tunai') {
        return redirect()->route('struk_tunai', $order->id);
    } else {
        return redirect()->route('pembayaran', $order->id);
    }
}

    


    
    public function pembayaran($id)
    {
        $order = Order::with('items')->find($id);
    
        if (!$order) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan.');
        }
    
        return view('pembayaran', compact('order'));
    }
    
    
}
