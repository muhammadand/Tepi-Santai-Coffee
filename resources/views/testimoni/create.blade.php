@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative hero-bg h-48 sm:h-60 flex items-center justify-center shadow-md rounded-b-xl">
  <div class="text-center">
    <h1 class="text-3xl sm:text-4xl font-bold text-amber-700">💬 Beri Testimoni</h1>
    <p class="text-sm text-gray-600 mt-2">Bagikan pengalamanmu menikmati {{ $product->name }} ☕</p>
  </div>
</div>

<!-- Form Section -->
<section class="max-w-2xl mx-auto px-6 py-10">
  @if (session('error'))
    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
      <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
  @endif

  <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-8 border border-amber-100">
    <form action="{{ route('testimoni.store') }}" method="POST" class="space-y-6">
      @csrf

      <input type="hidden" name="product_id" value="{{ $product->id }}">

      <!-- Product Preview -->
      <div class="flex items-center space-x-4 pb-4 border-b border-amber-100">
        <div class="w-16 h-16 rounded-lg overflow-hidden bg-amber-50 flex items-center justify-center">
          @if($product->foto)
            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
          @else
            <i class="fas fa-mug-saucer text-amber-400 text-2xl"></i>
          @endif
        </div>
        <div>
          <p class="text-lg font-semibold text-gray-800">{{ $product->name }}</p>
          <p class="text-sm text-gray-500">Katakan pendapatmu tentang produk ini</p>
        </div>
      </div>

      <!-- Rating -->
      <div>
        <label for="rating" class="block text-gray-700 font-medium mb-2">Rating</label>
        <select name="rating" id="rating" required
          class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
          <option value="">-- Pilih Rating --</option>
          @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }} ⭐</option>
          @endfor
        </select>
        @error('rating')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Content -->
      <div>
        <label for="content" class="block text-gray-700 font-medium mb-2">Isi Testimoni</label>
        <textarea name="content" id="content" rows="4" required
          class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">{{ old('content') }}</textarea>
        @error('content')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit -->
      <div class="flex justify-end">
        <button type="submit"
          class="amber-gradient text-white font-semibold px-6 py-3 rounded-full shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
          <i class="fas fa-paper-plane mr-2"></i> Kirim Testimoni
        </button>
      </div>
    </form>
  </div>
</section>
@endsection
