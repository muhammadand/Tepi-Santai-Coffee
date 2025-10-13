@extends('layouts.app')

@section('content')
<section class="relative bg-amber-100 rounded-xl shadow-md mb-6 overflow-hidden">
    <div class="px-6 py-6 sm:py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-amber-700">☕ {{ $title ?? 'Pembayaran' }}</h1>
            <p class="text-sm text-amber-800 mt-1">{{ $subtitle ?? 'Lakukan pembayaran segera ya!!' }}</p>
        </div>
        <div class="mt-4 sm:mt-0">
            {{-- Opsional: Tombol Aksi --}}
            @if (isset($actionButton))
                {!! $actionButton !!}
            @endif
        </div>
    </div>
</section>

<section class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-lg mt-10 font-[Poppins]">
    <h1 class="text-2xl font-bold mb-4 text-center text-amber-700">🧁 Konfirmasi Pembayaran</h1>

    <!-- Info Pemesan -->
    <div class="mb-6 border-b border-amber-200 pb-4 space-y-1 text-sm text-gray-700">
        <p><span class="font-semibold text-amber-600">Nama Pemesan:</span> {{ $order->nama }}</p>
        <p><span class="font-semibold text-amber-600">Alamat:</span> {{ $order->detail_alamat }}</p>
        <p><span class="font-semibold text-amber-600">Metode Pembayaran:</span> {{ ucfirst($order->payment_method) }}</p>
        <p class="text-gray-400 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>

    <!-- List Produk -->
    @php $total = 0; @endphp
    <div class="space-y-4">
        @foreach ($order->items as $item)
            @php
                $subtotal = $item->price * $item->quantity;
                $total += $subtotal;
            @endphp
            <div class="border border-amber-200 rounded-lg p-4 shadow-sm bg-amber-50">
                <div class="flex justify-between items-start mb-1 text-gray-800">
                    <span class="font-medium">{{ $item->produk }}</span>
                    <span class="text-sm text-gray-500">x{{ $item->quantity }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Harga</span>
                    <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-semibold mt-1 text-amber-700">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Total -->
    <div class="mt-6 border-t border-amber-200 pt-4 text-right">
        <p class="text-sm font-medium text-gray-700">Total Pembayaran:</p>
        <p class="text-2xl font-bold text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</p>
    </div>

    <!-- Tombol Bayar -->
    <form action="{{ route('payment.proses', $order->id) }}" method="POST" class="text-center mt-6">
        @csrf
        <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-full shadow-md inline-flex items-center transition-all duration-300 hover:bg-amber-700 hover:scale-105">
            <i class="fas fa-mug-saucer fa-sm mr-2"></i> Bayar Sekarang
        </button>
    </form>

    <p class="text-center text-gray-500 mt-6 text-sm">Terima kasih sudah memesan di <span class="text-amber-600 font-semibold">Tepi Santai Coffee</span> ☕</p>
</section>
@endsection
