@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-700">📂 Daftar Kategori</h1>

    <a href="{{ route('categories.create') }}"
        class="mb-4 inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 text-sm transition">
        Tambah Kategori
    </a>

    @if (session('success'))
        <div class="mb-4 p-3 bg-amber-100 text-amber-800 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mobile View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse ($categories as $index => $category)
            <div class="bg-white rounded-xl shadow p-4 border border-amber-100">
                <h2 class="text-lg font-semibold text-gray-700">{{ $category->name }}</h2>
                <div class="flex gap-4 mt-3">
                    <a href="{{ route('categories.edit', $category) }}"
                        class="text-amber-600 hover:text-amber-700 font-medium text-sm transition">Edit</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-700 font-medium text-sm transition">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada kategori.</p>
        @endforelse
    </div>

    {{-- Desktop/Tablet View --}}
    <div class="hidden md:block overflow-x-auto bg-white shadow-md rounded-xl border border-amber-100">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-amber-50 text-gray-700">
                <tr>
                    <th class="p-4 border-b border-amber-200">No</th>
                    <th class="p-4 border-b border-amber-200">Nama</th>
                    <th class="p-4 border-b border-amber-200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $index => $category)
                    <tr class="hover:bg-amber-50 transition">
                        <td class="p-4 border-b border-amber-200">{{ $categories->firstItem() + $index }}</td>
                        <td class="p-4 border-b border-amber-200 text-gray-700">{{ $category->name }}</td>
                        <td class="p-4 border-b border-amber-200 space-x-3">
                            <a href="{{ route('categories.edit', $category) }}"
                                class="text-amber-600 hover:text-amber-700 font-medium transition">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-700 font-medium transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-gray-500 text-center">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
