<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimoni;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TestimoniController extends Controller
{
    /**
     * Menampilkan semua testimoni milik user yang sedang login.
     */
    public function index()
    {
        $testimonis = Testimoni::with(['product', 'user'])
            ->latest()
            ->get();
    
        return view('testimoni.index', compact('testimonis'));
    }
    

    /**
     * Form untuk membuat testimoni baru berdasarkan order.
     */
    public function create($order_item_id)
    {
        $orderItem = \App\Models\OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($order_item_id);
    
        // Ambil produk berdasarkan nama produk dari order_item
        $product = \App\Models\Product::where('name', $orderItem->produk)->first();
    
        if (! $product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
    
        return view('testimoni.create', compact('product', 'orderItem'));
    }
    

    /**
     * Menyimpan testimoni ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500',
        ]);

        Testimoni::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'content' => $request->content,
        ]);

        return redirect()->route('orders.user')->with('success', 'Terima kasih! Testimoni kamu sudah dikirim.');
    }

    /**
     * Menghapus testimoni milik user.
     */
    public function destroy($id)
    {
        $testimoni = Testimoni::where('user_id', Auth::id())->findOrFail($id);
        $testimoni->delete();

        return redirect()->route('testimoni.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}
