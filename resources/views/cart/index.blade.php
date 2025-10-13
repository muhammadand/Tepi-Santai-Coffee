@extends('layouts.app')

@section('content')
<section class="mt-10 max-w-6xl mx-auto px-4 sm:px-6 py-10 bg-amber-50 rounded-2xl shadow-xl border border-amber-100 text-gray-900">
    <h2 class="text-2xl sm:text-4xl font-bold text-center text-amber-700 mb-10 tracking-tight">
        ☕ Lengkapi Data Pemesanan
    </h2>

    @php
        $cart = session('cart', []);
        $total = 0;
    @endphp

    @if (count($cart) > 0)
    <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @csrf

        <!-- Kiri: Produk -->
        <div class="space-y-5">
            @foreach ($cart as $id => $item)
                @php
                    $hasDiscount = isset($item['discount_active']) && $item['discount_active'];
                    $originalPrice = $hasDiscount ? $item['price'] + $item['discount'] : $item['price'];
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                @endphp

                <div class="bg-white border border-amber-200 rounded-xl shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-amber-700">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">Jumlah: <span class="font-medium">{{ $item['quantity'] }}</span></p>
                    </div>
                    <div class="text-right">
                        @if($hasDiscount)
                            <p class="text-sm line-through text-red-400">Rp {{ number_format($originalPrice, 0, ',', '.') }}</p>
                            <p class="text-sm font-semibold text-green-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm font-medium text-gray-700">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        @endif
                        <p class="text-base font-bold text-gray-800 mt-1">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kanan: Form -->
        <div class="bg-white border border-amber-200 rounded-xl shadow-sm p-6 space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div>
                    <label for="nama" class="block mb-1 font-medium text-amber-700">Nama Pemesan</label>
                    <input type="text" id="nama" name="nama" value="{{ Auth::check() ? Auth::user()->name : '' }}" required
                        class="w-full rounded-full border border-amber-200 px-4 py-2 bg-white placeholder-gray-400 focus:ring-2 focus:ring-amber-300 focus:outline-none transition text-sm" 
                        placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label for="detail_alamat" class="block mb-1 font-medium text-amber-700">Detail Alamat</label>
                    <input type="text" id="detail_alamat" name="detail_alamat"  value="{{ old('detail_alamat', $lastAlamat) }}"  required
                        class="w-full rounded-full border border-amber-200 px-4 py-2 bg-white placeholder-gray-400 focus:ring-2 focus:ring-amber-300 focus:outline-none transition text-sm" 
                        placeholder="Masukkan alamat lengkap">
                </div>
                <input type="hidden" name="payment_method" value="non-tunai">
            </div>

            <!-- Total dan tombol -->
            <div class="pt-4 border-t border-amber-200">
                <div class="flex justify-between items-center font-bold text-lg text-gray-900 mb-4">
                    <span>Total</span>
                    <span class="text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-full transition shadow-md hover:shadow-lg">
                    Konfirmasi Pesanan
                </button>
            </div>
        </div>
    </form>
    @else
        <p class="text-center text-gray-500 italic text-base sm:text-lg mt-6">Keranjang Anda kosong ☕</p>
    @endif
</section>
@endsection
