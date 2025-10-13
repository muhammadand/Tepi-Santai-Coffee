@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-8">

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
            📊 Laporan Analisis Penjualan (OLAP)
        </h2>
        <p class="text-gray-500 mt-2 text-sm">
            Analisis penjualan, pelanggan, dan efektivitas promo selama tahun {{ date('Y') }}.
        </p>
    </div>

    <!-- Filter Tanggal -->
    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col md:flex-row items-center gap-4">
            <div>
                <label class="text-sm text-gray-700 font-medium">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            </div>
            <div>
                <label class="text-sm text-gray-700 font-medium">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            </div>
            <div class="flex gap-3 mt-4 md:mt-6">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Top Penjualan Bulanan -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">📅 Penjualan per Bulan</h4>
            <canvas id="penjualanChart" class="w-full h-48"></canvas>
        </div>

        <!-- Top Pelanggan -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">👑 Top 5 Pelanggan Aktif</h4>
            <ul class="space-y-2">
                @foreach($topPelanggan as $p)
                    <li class="flex justify-between items-center border-b pb-1 text-gray-700">
                        <span class="font-medium">{{ $p->name }}</span>
                        <span class="text-amber-500 font-semibold">{{ $p->total_transaksi }}x</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Efektivitas Promo -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">🎯 Efektivitas Promo</h4>
            <ul class="space-y-2">
                @foreach ($efektivitasPromo as $promo)
                    <li class="flex justify-between items-center border-b pb-1 text-gray-700">
                        <span>{{ $promo->name }}</span>
                        <span class="text-green-500 font-semibold">{{ $promo->total_terjual }} terjual</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Grid Produk & Kategori -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Top Produk Terlaris -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">🏆 Top 5 Produk Terlaris</h4>
            <ul class="space-y-2">
                @foreach ($topProduk as $p)
                    <li class="flex justify-between border-b pb-1 text-gray-700">
                        <span>{{ $p->produk }}</span>
                        <span class="text-indigo-600 font-semibold">{{ $p->total_terjual }} terjual</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Kategori Favorit -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">🗂️ Kategori Paling Diminati</h4>
            <ul class="space-y-2">
                @foreach ($kategoriFavorit as $k)
                    <li class="flex justify-between border-b pb-1 text-gray-700">
                        <span>{{ $k->kategori }}</span>
                        <span class="text-purple-500 font-semibold">{{ $k->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Laporan Penjualan Detail -->
    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 mt-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">📋 Laporan Penjualan Detail</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-indigo-50 text-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-center">Jumlah</th>
                        <th class="px-4 py-2 text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($laporanPenjualan as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->tanggal }}</td>
                            <td class="px-4 py-2">{{ $item->produk }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->jumlah }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data untuk periode ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Penjualan per Bulan
    const penjualanChart = new Chart(document.getElementById('penjualanChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($penjualanPerBulan->toArray())) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode(array_values($penjualanPerBulan->toArray())) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
