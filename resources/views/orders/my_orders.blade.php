@extends('layouts.app')

@section('content')
    <!-- Hero Header -->
    <div class="relative bg-cover bg-center h-64 sm:h-80 md:h-[22rem] rounded-b-xl shadow-xl overflow-hidden"
        style="background-image: url('https://images.unsplash.com/photo-1606811843603-f5172dd8253d?auto=format&fit=crop&w=1200&q=80');">
        <div class="absolute inset-0 bg-gradient-to-t from-amber-700/80 via-amber-600/70 to-transparent flex items-end">
            <div class="px-6 pb-6">
                <h1 class="text-4xl font-bold text-white">☕ Pesanan Saya</h1>
                <p class="text-sm text-amber-100 mt-1">Lihat semua pesanan kopi & kue favoritmu yang sudah dibuat 🍰</p>
            </div>
        </div>
    </div>

    <!-- Daftar Pesanan -->
    <section class="bg-amber-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8 text-gray-800 font-[Poppins]">
        <div class="max-w-6xl mx-auto">
            @forelse ($orders as $order)
                <div
                    class="bg-white border border-amber-200 rounded-xl p-6 mb-6 shadow hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4">
                        <h3 class="text-xl font-bold text-amber-700">#{{ $order->id }}</h3>
                        <span class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <!-- Badge Status -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="bg-amber-200 text-amber-800 text-xs px-3 py-1 rounded-full uppercase tracking-wide">
                            {{ ucfirst($order->status_order) }}
                        </span>

                        @if ($order->status_order === 'selesai')
                            <div class="mt-2 text-sm text-gray-600 italic">
                                Pesananmu sudah diterima 🍀 Terima kasih sudah berbelanja!
                            </div>
                        @endif

                        <span class="bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full">
                            {{ ucfirst($order->payment_method) }}
                        </span>
                        <span class="bg-amber-50 text-amber-700 text-xs px-3 py-1 rounded-full border border-amber-200">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <!-- Detail Item -->
                    <ul class="text-sm space-y-4 text-gray-700">
                        @foreach ($order->items as $item)
                            <li
                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-amber-200 pb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-mug-saucer text-amber-500 mr-2"></i>
                                    <span>
                                        {{ $item->produk }}
                                        <span class="text-xs text-gray-400">×{{ $item->quantity }}</span>
                                    </span>
                                </div>
                                <div class="mt-2 sm:mt-0 flex items-center gap-3">
                                    <span class="font-semibold text-gray-900">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>

                                    <!-- Tombol Testimoni -->
                                    @if ($order->status_order === 'selesai')
                                        <a href="{{ route('testimoni.create', $item->id) }}"
                                            class="mt-4 inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-full text-sm shadow-md">
                                            ✍️ Beri Testimoni untuk {{ $item->produk }}
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tombol Bayar -->
                    @if ($order->payment_status === 'pending')
                        <form action="{{ route('payment.proses', $order->id) }}" method="POST" class="mt-6 text-center">
                            @csrf
                            <button type="submit"
                                class="bg-amber-600 hover:bg-amber-700 text-white text-sm px-6 py-2 rounded-full font-semibold shadow-md hover:shadow-lg inline-flex items-center transition-all duration-300">
                                <i class="fas fa-mug-saucer mr-2"></i> Bayar Sekarang
                            </button>
                        </form>
                    @endif

                    <!-- ✅ Tombol "Tandai Diterima" hanya muncul jika status masih dipesan -->
                    @if ($order->status_order === 'dipesan')
                        <form action="{{ route('orders.updateStatus.status', [$order->id, 'status' => 'selesai']) }}" method="POST" class="mt-6 text-center">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white text-sm px-6 py-2 rounded-full font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                ✅ Tandai Sudah Diterima
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-center mt-20 text-gray-400">
                    <i class="fas fa-box-open text-5xl text-amber-400 mb-4"></i>
                    <p class="text-lg font-semibold">Belum ada pesanan</p>
                    <p class="text-sm text-gray-500">Saatnya manjakan dirimu dengan pesanan kopi & kue hari ini ☕🍰</p>
                </div>
            @endforelse
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#fff8e1',
                    color: '#4e342e',
                    iconColor: '#ffb300'
                });
            @endif
    
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonColor: '#d32f2f',
                    background: '#fff8e1',
                    color: '#4e342e',
                    iconColor: '#e64a19'
                });
            @endif
        });
    </script>
@endsection
