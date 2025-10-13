@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @if (Auth::check())
        <div class="flex justify-between items-center mb-4">
            <div class="text-gray-700 font-semibold">
                @if (Auth::check())
                    @if (Auth::user()->role === 'admin')
                        Dashboard <span class="text-amber-600">Admin</span><br>
                        👤 Anda login sebagai <span class="text-amber-600">{{ ucfirst(Auth::user()->role) }}</span>
                    @elseif(Auth::user()->role === 'kasir')
                        Dashboard <span class="text-amber-600">Kasir</span><br>
                        👤 Anda login sebagai <span class="text-amber-600">{{ ucfirst(Auth::user()->role) }}</span>
                    @else
                        Dashboard <span class="text-amber-600">{{ ucfirst(Auth::user()->role) }}</span><br>
                        👤 Anda login sebagai <span class="text-amber-600">{{ ucfirst(Auth::user()->role) }}</span>
                    @endif
                @endif

            </div>

            {{-- Form tampil tahun hanya untuk admin atau kasir --}}
            @if (in_array(Auth::user()->role, ['admin']))
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center space-x-2">
                    <label for="year" class="font-semibold text-gray-700">Pilih Tahun:</label>
                    <select name="year" id="year" class="border border-gray-300 rounded px-2 py-1">
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700">
                        Tampilkan
                    </button>
                </form>
            @endif
        </div>
    @endif


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Card: Total Produk -->
        <a href="{{ route('products.index') }}" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow flex items-center space-x-4 border border-amber-100">
                <div class="text-amber-600 text-4xl"><i class="fas fa-box-open"></i></div>
                <div>
                    <h2 class="text-gray-600 font-semibold">Total Produk</h2>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $totalProducts }}</p>
                </div>
            </div>
        </a>

        <!-- Card: Total Pesanan -->
        <a href="{{ route('orders.index') }}" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow flex items-center space-x-4 border border-amber-100">
                <div class="text-amber-500 text-4xl"><i class="fas fa-shopping-cart"></i></div>
                <div>
                    <h2 class="text-gray-600 font-semibold">Total Pesanan</h2>
                    <p class="mt-2 text-3xl font-bold text-amber-500">{{ $totalOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Card: Pending -->
        <a href="{{ route('orders.index') }}" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow flex items-center space-x-4 border border-amber-100 hover:bg-amber-50 cursor-pointer">
                <div class="text-amber-400 text-4xl">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h2 class="text-gray-600 font-semibold">Menunggu Pembayaran</h2>
                    <p class="mt-2 text-3xl font-bold text-amber-400">{{ $pendingOrders }}</p>
                </div>
            </div>
        </a>

    </div>


@if(Auth::check() && Auth::user()->role === 'admin')
    <!-- Analisis OLAP -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Penjualan per Bulan -->
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow border border-amber-100">
            <h2 class="text-gray-700 font-semibold mb-4 text-lg">📈 Penjualan Per Bulan ({{ $year }})</h2>
            <canvas id="penjualanChart" class="w-full h-64"></canvas>
        </div>

        <!-- Top 5 Produk Terlaris -->
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow border border-amber-100">
            <h2 class="text-gray-700 font-semibold mb-4 text-lg">🏆 Top 5 Produk Terlaris</h2>
            <ul class="space-y-2">
                @foreach ($topProduk as $p)
                    <li class="flex justify-between items-center bg-amber-50 rounded px-3 py-2">
                        <span>{{ $p->produk }}</span>
                        <span class="font-semibold text-amber-600">{{ $p->total_terjual }} terjual</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Kategori Paling Diminati -->
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow border border-amber-100">
            <h2 class="text-gray-700 font-semibold mb-4 text-lg">📂 Kategori Paling Diminati</h2>
            <ul class="space-y-2">
                @foreach ($kategoriFavorit as $k)
                    <li class="flex justify-between items-center bg-amber-50 rounded px-3 py-2">
                        <span>{{ $k->kategori }}</span>
                        <span class="font-semibold text-amber-600">{{ $k->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Pelanggan Aktif Per Bulan -->
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow border border-amber-100">
            <h2 class="text-gray-700 font-semibold mb-4 text-lg">👥 Pelanggan Aktif Per Bulan</h2>
            <canvas id="pelangganChart" class="w-full h-64"></canvas>
        </div>
    </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const penjualanChart = new Chart(document.getElementById('penjualanChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($penjualanPerBulan->toArray())) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode(array_values($penjualanPerBulan->toArray())) !!},
                    backgroundColor: 'rgba(251, 191, 36, 0.7)',
                    borderColor: 'rgba(251, 191, 36, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const pelangganChart = new Chart(document.getElementById('pelangganChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($pelangganAktif->toArray())) !!},
                datasets: [{
                    label: 'Pelanggan Aktif',
                    data: {!! json_encode(array_values($pelangganAktif->toArray())) !!},
                    fill: true,
                    backgroundColor: 'rgba(251, 191, 36, 0.3)',
                    borderColor: 'rgba(251, 191, 36, 1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
