@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Daftar Pesanan</h1>

        {{-- ✅ Daftar Pesanan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Pesanan Dipesan --}}
            <div>
                <h2 class="text-lg font-semibold mb-4 text-blue-700">📌 Pesanan Dipesan</h2>
                @forelse ($orders->where('status_order', 'dipesan') as $order)
                    @include('components.order-card', ['order' => $order])
                @empty
                    <p class="text-sm text-gray-500">Tidak ada pesanan baru.</p>
                @endforelse
            </div>

            {{-- Pesanan Diterima --}}
            <div>
                <h2 class="text-lg font-semibold mb-4 text-green-700">✅ Pesanan Diterima</h2>
                @forelse ($orders->where('status_order', 'diterima') as $order)
                    @include('components.order-card', ['order' => $order])
                @empty
                    <p class="text-sm text-gray-500">Belum ada pesanan yang diterima.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
