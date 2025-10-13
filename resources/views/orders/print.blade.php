@extends('layouts.app')

@section('content')
<section class="relative hero-bg rounded-xl shadow-md mb-6 overflow-hidden border border-amber-200">
    <div class="px-6 py-6 sm:py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-amber-700">{{ $title ?? 'Struk Pemesanan' }}</h1>
            <p class="text-sm text-amber-600 mt-1">{{ $subtitle ?? 'Terima kasih telah memesan di Tepi Santai Coffee' }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            {{-- Informasi Login --}}
            @auth
                <span class="inline-flex items-center bg-amber-100 text-amber-700 text-sm font-medium px-3 py-1 rounded-full shadow-sm">
                    <i class="fas fa-user-circle mr-2"></i>
                    {{ Auth::user()->role === 'admin' ? 'Admin' : 'Kasir' }}
                </span>
            @endauth
        </div>
    </div>
</section>

<div id="struk-card" class="mt-10 max-w-md mx-auto bg-white border border-amber-100 rounded-xl shadow-lg p-6 font-[Poppins] text-gray-800">
    <h2 class="text-lg sm:text-xl font-extrabold mb-6 text-center text-amber-600 border-b border-amber-200 pb-3">
        ☕ Struk Pesanan #TS-{{ $order->id }}
    </h2>

    <div class="mb-5 text-sm space-y-1">
        <p><span class="font-semibold text-amber-600">Nama:</span> {{ $order->nama }}</p>
        <p><span class="font-semibold text-amber-600">Alamat:</span> {{ $order->detail_alamat }}</p>
        <p><span class="font-semibold text-amber-600">Tanggal:</span> {{ $order->created_at->format('d-m-Y H:i') }}</p>
        <p>
            <span class="font-semibold text-amber-600">Status Pembayaran:</span>
            <span class="uppercase tracking-wide font-semibold
                {{ $order->payment_status === 'paid' ? 'text-green-600' : '' }}
                {{ $order->payment_status === 'pending' ? 'text-yellow-600' : '' }}
                {{ in_array($order->payment_status, ['failed', 'cancelled']) ? 'text-red-600' : '' }}">
                {{ $order->payment_status }}
            </span>
        </p>
    </div>

    <!-- Produk -->
    <div class="bg-amber-50 rounded-lg border border-amber-100 p-4 text-sm shadow-sm mb-5">
        @foreach ($order->items as $item)
            <div class="flex justify-between border-b border-amber-100 py-1 last:border-0">
                <div class="truncate w-1/2">{{ $item->produk ?? '-' }}</div>
                <div class="w-1/6 text-right">{{ $item->quantity }}x</div>
                <div class="w-1/3 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
            </div>
        @endforeach
    </div>

    <!-- Total -->
    <div class="flex justify-between font-bold text-sm sm:text-base text-amber-700 border-t border-amber-200 pt-3">
        <span>Total</span>
        <span>Rp {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
    </div>

    <!-- Tombol -->
    <div class="mt-6 text-center space-x-2">
        <a href="{{ route('orders.index') }}" 
           class="inline-block border border-amber-500 text-amber-600 px-5 py-2 rounded-full hover:bg-amber-500 hover:text-white transition text-sm font-semibold shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu
        </a>

        <button 
           onclick="printStruk()" 
           class="inline-block bg-amber-600 text-white px-5 py-2 rounded-full hover:bg-amber-700 transition text-sm font-semibold shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Struk
        </button>
    </div>
</div>



<script>
    function printStruk() {
        const printContents = document.getElementById('struk-card').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

        // reload halaman setelah print selesai
        window.location.reload();
    }
</script>
@endsection
