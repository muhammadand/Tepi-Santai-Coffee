@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- ✅ Pesan sukses --}}
    @if(session('success'))
        <div class="mb-4 text-green-600 bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Produk</h1>
        <a href="{{ route('products.create') }}" 
           class="bg-amber-700 hover:bg-amber-800 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200 text-sm">
            + Tambah Produk
        </a>
    </div>

    {{-- ✅ Filter --}}
    <form method="GET" class="flex flex-col md:flex-row gap-2 mb-6">
        <input type="text" name="search" placeholder="Cari produk..." 
               value="{{ request('search') }}"
               class="border border-slate-300 rounded px-3 py-1.5 text-sm flex-1 focus:ring-amber-500 focus:border-amber-500">
        <label class="flex items-center text-sm gap-1">
            <input type="checkbox" name="only_discount" value="1" 
                   {{ request('only_discount') ? 'checked' : '' }}
                   class="rounded border-slate-300">
            Hanya yang diskon
        </label>
        <button class="bg-amber-700 hover:bg-amber-800 text-white px-3 py-1.5 rounded text-sm shadow-sm">
            Filter
        </button>
    </form>

    {{-- ✅ Tabel --}}
    <div class="hidden md:block overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 border-b border-slate-300">Foto</th>
                    <th class="p-4 border-b border-slate-300">Nama Produk</th>
                    <th class="p-4 border-b border-slate-300">Harga</th>
                    <th class="p-4 border-b border-slate-300">Diskon</th>
                    <th class="p-4 border-b border-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 border-b border-slate-200">
                        <img src="{{ asset('storage/' . $product->foto) }}" 
                             alt="{{ $product->name }}" 
                             class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="p-4 border-b border-slate-200 font-medium text-slate-800">
                        {{ $product->name }}
                        @if($product->discount_active)
                            <span class="ml-1 bg-green-100 text-green-700 px-2 py-0.5 text-xs rounded">Aktif</span>
                        @endif
                    </td>
                    <td class="p-4 border-b border-slate-200">
                        Rp {{ number_format($product->price, 0, ',', '.') }} <br>
                        @if ($product->discount_active && $product->discount)
                            <span class="text-red-600 text-xs">Harga Diskon: Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                        @endif
                    </td>

                    {{-- ✅ Kolom Diskon --}}
                    <td class="p-4 border-b border-slate-200">
                        <form action="{{ route('products.setDiscount', $product->id) }}" method="POST" class="space-y-2">
                            @csrf
                            <input type="number" step="0.01" name="discount" 
                                   value="{{ $product->discount }}" 
                                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-amber-500 focus:border-amber-500" 
                                   placeholder="Diskon (Rp)">

                            <label class="inline-flex items-center text-sm">
                                <input type="checkbox" name="discount_active" value="1" 
                                       {{ $product->discount_active ? 'checked' : '' }} 
                                       class="mr-2 rounded border-slate-300">
                                Aktifkan Diskon
                            </label>

                            {{-- 🔹 User yang sudah dipilih --}}
                            @if(!empty($product->discount_user_ids))
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($users->whereIn('id', $product->discount_user_ids) as $u)
                                        <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full border border-amber-200">
                                            {{ $u->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- 🔹 Daftar User --}}
                            <div x-data="{ open: false }" class="mt-2">
                                <button @click="open = !open" type="button" 
                                        class="text-xs text-slate-600 hover:text-amber-700 transition">
                                    <span x-show="!open">+ Tampilkan Daftar User</span>
                                    <span x-show="open">− Sembunyikan Daftar User</span>
                                </button>

                                <div x-show="open" x-transition class="mt-2 bg-slate-50 border border-slate-200 rounded-lg p-2">
                                    <input type="text" 
                                           placeholder="Cari user..." 
                                           class="w-full mb-2 px-2 py-1 text-xs border border-slate-300 rounded"
                                           oninput="filterUsers(this, {{ $product->id }})">

                                    <div id="user-list-{{ $product->id }}" class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                                        @foreach ($users as $user)
                                            <label class="user-item flex items-center text-xs bg-white px-2 py-1 rounded border border-slate-300 hover:bg-slate-100 transition">
                                                <input 
                                                    type="checkbox" 
                                                    name="discount_user_ids[]" 
                                                    value="{{ $user->id }}"
                                                    {{ is_array($product->discount_user_ids) && in_array($user->id, $product->discount_user_ids) ? 'checked' : '' }}
                                                    class="mr-2 rounded border-slate-300">
                                                {{ $user->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="bg-amber-700 hover:bg-amber-800 text-white font-medium px-3 py-1.5 rounded-lg text-sm transition duration-200 active:scale-95 shadow-sm w-full">
                                Simpan Diskon
                            </button>
                        </form>
                    </td>

                    {{-- ✅ Aksi --}}
                    <td class="p-4 border-b border-slate-200 space-x-2">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Yakin ingin hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ Script tambahan --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function filterUsers(input, productId) {
    const query = input.value.toLowerCase();
    const list = document.querySelector(`#user-list-${productId}`);
    list.querySelectorAll('.user-item').forEach(el => {
        const name = el.textContent.toLowerCase();
        el.style.display = name.includes(query) ? '' : 'none';
    });
}
</script>
@endsection
