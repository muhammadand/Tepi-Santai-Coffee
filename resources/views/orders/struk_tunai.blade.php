@extends('layouts.app')

@section('content')
<div id="receipt" class="max-w-xs mx-auto p-4 mt-8 bg-white border border-gray-300 rounded-lg shadow-md font-mono text-gray-900 text-sm sm:text-base">
    <h2 class="text-xl font-bold mb-4 text-center border-b border-gray-400 pb-2 tracking-wide">
        STRUK PEMBAYARAN TUNAI #TUN-{{ $order->id }}
    </h2>

    <div class="mb-3 space-y-1">
        <p><span class="font-semibold">Nama:</span> {{ $order->nama }}</p>
        <p><span class="font-semibold">Meja:</span> #{{ $order->meja }}</p>
        <p><span class="font-semibold">Tanggal:</span> {{ $order->created_at->format('d-m-Y H:i') }}</p>
        <p><span class="font-semibold">Status Pembayaran:</span> Tunai</p>
    </div>

    <div class="border-t border-b border-gray-400 py-2 mb-4">
        @foreach ($order->items as $item)
        <div class="flex justify-between border-b border-gray-300 py-1 last:border-0 text-xs sm:text-sm">
            <div class="w-1/2 truncate">{{ $item->produk ?? '-' }}</div>
            <div class="w-1/6 text-right">{{ $item->quantity }}x</div>
            <div class="w-1/4 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>

    <div class="flex justify-between font-bold text-base sm:text-lg border-t border-gray-400 pt-2">
        <span>Total</span>
        <span>Rp {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
    </div>

    <div class="mt-4 text-center no-print space-x-2">
        <a href="{{ route('menu.index') }}"
           class="inline-block border border-gray-800 px-3 py-1.5 rounded-md hover:bg-gray-800 hover:text-white transition text-xs sm:text-sm">
            Kembali ke Menu
        </a>
    
        <button onclick="window.print()"
                class="inline-block border border-red-200 text-red-300 hover:bg-reed-200   hover:text-red px-3 py-1.5 rounded-md transition text-xs sm:text-sm">
            Cetak Struk
        </button>
    </div>
    
</div>

{{-- Hanya style untuk cetak --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt, #receipt * {
            visibility: visible;
        }
        #receipt {
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('order_success'))
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('order_success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'text-sm sm:text-base p-4 sm:p-6',
                    title: 'text-lg font-semibold mb-2',
                    content: 'text-sm mb-4',
                    confirmButton: 'bg-black hover:bg-green-700 text-white font-semibold px-5 py-2 rounded shadow-sm transition'
                },
                buttonsStyling: false
            });
        @endif
    });
</script>
@endsection
