@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-amber-700">Tambah Produk Baru</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div>
            <label for="name" class="block font-semibold mb-2 text-gray-700">Nama Produk</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 transition duration-200" />
        </div>

        <div>
            <label for="category_id" class="block font-semibold mb-2 text-gray-700">Kategori</label>
            <select id="category_id" name="category_id" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 transition duration-200">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="price" class="block font-semibold mb-2 text-gray-700">Harga</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 transition duration-200" />
        </div>

        <div>
            <label for="foto" class="block font-semibold mb-2 text-gray-700">Foto Produk</label>
            <input type="file" id="foto" name="foto" required accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 transition duration-200" />
        </div>

        <button type="submit" 
            class="bg-amber-700 hover:bg-amber-800 text-white font-medium px-6 py-2 rounded-lg shadow-md transition duration-200 active:scale-95">
            Simpan Produk
        </button>
    </form>
</div>
@endsection
