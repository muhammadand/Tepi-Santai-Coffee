@extends('layouts.admin')

@section('content')
<div class="max-w-md mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-gray-700">📂 Tambah Kategori Baru</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-amber-100 text-amber-800 rounded shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" class="space-y-5 bg-white p-6 rounded-xl shadow-md border border-amber-100">
        @csrf
        <div>
            <label for="name" class="block mb-2 font-semibold text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                class="w-full border border-amber-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400" 
                placeholder="Masukkan nama kategori" required />
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit" 
                class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 font-medium transition">
                Simpan
            </button>
            <a href="{{ route('categories.index') }}" 
                class="text-gray-600 hover:text-gray-800 font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
