@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl mb-4 font-semibold">Daftar Feedback Pengguna</h1>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    {{-- Mobile Layout --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse ($feedbacks as $fb)
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm"><span class="font-semibold">Nama:</span> {{ $fb->nama }}</p>
                <p class="text-sm"><span class="font-semibold">Email:</span> {{ $fb->email }}</p>
                <p class="text-sm"><span class="font-semibold">Rating:</span> {{ str_repeat('⭐', $fb->rating) }}</p>
                <p class="text-sm"><span class="font-semibold">Pesan:</span> {{ $fb->pesan }}</p>
                <p class="text-xs text-gray-500 mt-2">📅 {{ $fb->created_at->format('d M Y H:i') }}</p>
            </div>
        @empty
            <div class="bg-white p-4 rounded shadow text-center text-gray-500">Belum ada feedback.</div>
        @endforelse
    </div>

    {{-- Desktop Layout --}}
    <div class="hidden md:block overflow-x-auto bg-white shadow-md rounded-lg mt-4">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Rating</th>
                    <th class="p-4">Pesan</th>
                    <th class="p-4">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($feedbacks as $index => $fb)
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="p-4">{{ $feedbacks->firstItem() + $index }}</td>
                        <td class="p-4">{{ $fb->nama }}</td>
                        <td class="p-4">{{ $fb->email }}</td>
                        <td class="p-4 text-yellow-500">{{ str_repeat('⭐', $fb->rating) }}</td>
                        <td class="p-4">{{ $fb->pesan }}</td>
                        <td class="p-4 text-gray-500">{{ $fb->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada feedback.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection
