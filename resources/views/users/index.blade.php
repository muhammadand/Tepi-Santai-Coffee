@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-poppins">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Pelanggan</h1>

        <div class="mb-4">
            <button type="button" id="mailto-btn"
                class="bg-amber-700 hover:bg-amber-800 text-amber-50 text-sm px-4 py-2 rounded-lg font-medium transition duration-200 active:scale-95 shadow-sm">
                Kirim Email
            </button>

        </div>

        <div class="overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">
                            <input type="checkbox" id="select-all" class="form-checkbox w-4 h-4 text-blue-600">
                        </th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Total Order</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="py-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="user-checkbox form-checkbox w-4 h-4 text-blue-600"
                                    value="{{ $user->email }}">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $user->orders_count }}x
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->orders_count > 5)
                                    <span
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M3 10h11M9 21V3m12 13h-6m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                        Follow Up
                                    </span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-medium">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="text-blue-600 hover:underline text-sm">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin hapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>



    <script>
        // Centang semua user
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
        });

        // Kirim email pakai mailto
        document.getElementById('mailto-btn').addEventListener('click', function() {
            const emails = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.value);

            if (emails.length === 0) {
                alert('Pilih minimal satu pengguna untuk mengirim email.');
                return;
            }

            const subject = encodeURIComponent("Follow Up dari Admin");
            const body = encodeURIComponent(
                "Hai! Kami punya penawaran spesial untuk kamu 🎉. Dapatkan akses ke layanan premium seperti Canva Pro, Netflix, dan lainnya dengan harga terjangkau. Yuk, jangan lewatkan kesempatan ini!"
                );


            const mailto = `mailto:${emails.join(',')}?subject=${subject}&body=${body}`;
            window.location.href = mailto;
        });
    </script>
@endsection
