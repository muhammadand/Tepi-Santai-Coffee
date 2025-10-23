@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 mt-10 rounded-xl shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">
        📦 Layanan Ongkir Wilayah Kabupaten Kuningan
    </h2>

    <!-- Search -->
    <form method="GET" action="{{ route('layanan.ongkir') }}" class="mb-6 flex items-center justify-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari kecamatan, desa, atau kabupaten..."
               class="w-1/2 p-2 border border-gray-300 rounded-l-lg focus:ring-amber-500 focus:border-amber-500 outline-none">
        <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded-r-lg hover:bg-amber-700 transition">
            Cari
        </button>
    </form>

    <!-- Tabel -->
    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-amber-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Kabupaten</th>
                    <th class="px-4 py-3 text-left font-semibold">Kecamatan</th>
                    <th class="px-4 py-3 text-left font-semibold">Desa</th>
                    <th class="px-4 py-3 text-right font-semibold">Tarif Ongkir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($dataOngkir as $index => $item)
                    <tr class="hover:bg-amber-50 transition">
                        <td class="px-4 py-3 text-gray-700">
                            {{ $dataOngkir->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3">{{ $item['kabupaten'] }}</td>
                        <td class="px-4 py-3">{{ $item['kecamatan'] }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $item['desa'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-amber-700">
                            Rp {{ number_format($item['tarif_ongkir'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 italic">
                            Tidak ada data wilayah yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $dataOngkir->links('pagination::tailwind') }}
    </div>

    <p class="text-gray-500 text-xs text-center mt-4">
        *Tarif ongkir bersifat estimasi (acak antara Rp10.000 – Rp15.000)
    </p>
</div>
@endsection
