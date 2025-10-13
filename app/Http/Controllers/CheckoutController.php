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
            'detail_alamat' => 'required|string|max:255',
            'payment_method' => 'required|in:tunai,non-tunai',
        ]);
    
        // Ambil data cart dari session
        $cart = session('cart', []);
    
        // Jika keranjang kosong, redirect dengan error
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }
    
        // Simpan order ke database
        $order = Order::create([
            'user_id' => Auth::id(), // Wajib disertakan untuk menghindari error 1364
            'nama' => $request->nama,
            'detail_alamat' => $request->detail_alamat,
            'status_order' => 'dipesan',
            'payment_method' => $request->payment_method,
        ]);
    
        // Simpan semua item dalam order
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'produk' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    
        // Kosongkan keranjang setelah order berhasil disimpan
        session()->forget('cart');
    
        // Flash message untuk notifikasi
        session()->flash('order_success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran ke kasir supaya pesanan cepat diproses.');
    
        // Redirect ke halaman sesuai metode pembayaran
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
