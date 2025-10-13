<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan semua produk
    public function index()
    {
        $products = Product::with('category')->get();
    
        // Ambil semua user untuk form checkbox nanti
        $users = \App\Models\User::select('id', 'name')->get();
    
        return view('products.index', compact('products', 'users'));
    }
    
    // Form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'foto' => 'required|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Upload foto
        $path = $request->file('foto')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'foto' => $path,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form edit produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Jika ada foto baru, hapus foto lama dan upload yang baru
        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $path = $request->file('foto')->store('products', 'public');
            $product->foto = $path;
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }


    public function setDiscount(Request $request, $id)
    {
        $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'discount_user_ids' => 'nullable|array',
            'discount_user_ids.*' => 'exists:users,id',
        ]);
    
        $product = Product::findOrFail($id);
    
        // 🔹 Update nilai diskon dan status aktif
        if ($request->has('discount_active') && $request->discount > 0) {
            $product->discount = $request->discount;
            $product->discount_active = true;
        } else {
            $product->discount = 0;
            $product->discount_active = false;
        }
    
        // 🔹 Simpan user yang berhak dapat diskon (format JSON)
        $product->discount_user_ids = $request->discount_user_ids ?? [];
    
        $product->save();
    
        return redirect()->back()->with('success', 'Diskon produk berhasil diperbarui.');
    }
    
    

}
