@extends('layouts.app')

@section('content')
<section class="relative hero-bg rounded-xl shadow-md mb-6 overflow-hidden border border-amber-100">
    <div class="px-6 py-6 sm:py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-amber-600">
                {{ $title ?? 'Struk Pemesanan' }}
            </h1>
            <p class="text-sm text-amber-500 mt-1">
                {{ $subtitle ?? 'Terima kasih telah memesan di Tepi Santai Coffee ☕' }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            @if (isset($actionButton))
                {!! $actionButton !!}
            @endif
        </div>
    </div>
</section>

<div class="mt-10 max-w-md mx-auto collection-card border border-amber-100 rounded-2xl shadow-xl p-6 text-gray-800">
    <h2 class="text-lg sm:text-xl font-extrabold mb-6 text-center text-amber-600 border-b border-amber-200 pb-3">
        🧾 Struk Pesanan #NON-{{ $order->id }}
    </h2>

    <!-- Informasi Pesanan -->
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

    <!-- Daftar Produk -->
    <div class="bg-white/80 rounded-lg border border-amber-100 p-4 text-sm shadow-sm mb-5 backdrop-blur-md">
        @foreach ($order->items as $item)
            <div class="flex justify-between border-b border-amber-100 py-1 last:border-0">
                <div class="truncate w-1/2 text-gray-700">{{ $item->produk ?? '-' }}</div>
                <div class="w-1/6 text-right text-gray-600">{{ $item->quantity }}x</div>
                <div class="w-1/3 text-right text-gray-800 font-medium">
                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Total -->
    <div class="flex justify-between font-bold text-sm sm:text-base text-amber-700 border-t border-amber-100 pt-3">
        <span>Total</span>
        <span>Rp {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-6 flex justify-center space-x-3">
        <button onclick="printStruk()" 
            class="inline-flex items-center bg-gradient-to-r from-amber-400 to-amber-500 text-white px-5 py-2 rounded-full hover:from-amber-500 hover:to-amber-600 transition text-sm font-semibold shadow card-hover">
            <i class="fas fa-print mr-2"></i> Cetak Struk
        </button>

        <a href="{{ route('menu.index') }}" 
           class="inline-flex items-center border border-amber-400 text-amber-600 px-5 py-2 rounded-full hover:bg-amber-400 hover:text-white transition text-sm font-semibold shadow-sm card-hover">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Menu
        </a>
    </div>
</div>

<script>
function printStruk() {
    const content = document.querySelector('.collection-card').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Struk Pemesanan #{{ $order->id }}</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                body { font-family: 'Poppins', sans-serif; background: #fffaf5; padding: 20px; color: #444; }
                .title { text-align:center; color:#d97706; font-weight:bold; margin-bottom:15px; }
                table { width:100%; border-collapse:collapse; margin-top:10px; font-size:14px; }
                td, th { border-bottom:1px solid #fcd34d; padding:6px 4px; text-align:left; }
                .total { font-weight:bold; color:#b45309; text-align:right; margin-top:10px; }
            </style>
        </head>
        <body>
            <h2 class="title">☕ Tepi Santai Coffee</h2>
            ${content}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
