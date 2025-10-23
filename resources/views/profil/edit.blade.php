@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg mt-12 border border-gray-100">
    <h2 class="text-3xl font-semibold mb-6 text-amber-700 text-center">Edit Profil</h2>

    @if (session('success'))
        <div class="p-3 mb-5 bg-green-100 text-green-700 rounded-lg text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profil.update') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>

            <!-- Kabupaten -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Kabupaten</label>
                <select id="kabupaten" name="kabupaten"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($wilayahData as $kab => $data)
                        <option value="{{ $kab }}" {{ old('kabupaten', $user->kabupaten) == $kab ? 'selected' : '' }}>
                            {{ $kab }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kecamatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Kecamatan</label>
                <select id="kecamatan" name="kecamatan"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    <option value="">-- Pilih Kecamatan --</option>
                </select>
            </div>

            <!-- Desa -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Desa</label>
                <select id="desa" name="desa"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    <option value="">-- Pilih Desa --</option>
                </select>
            </div>

            <!-- Detail Alamat -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Detail Alamat</label>
                <textarea name="detail_alamat" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">{{ old('detail_alamat', $user->detail_alamat) }}</textarea>
            </div>

            <!-- Password -->
            <div class="md:col-span-2 border-t pt-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password"
                    class="w-full p-3 border border-gray-300 rounded-xl mb-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    placeholder="Masukkan password baru jika ingin mengubah">
                <input type="password" name="password_confirmation"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    placeholder="Konfirmasi password baru">
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-8 text-right">
            <button type="submit"
                class="px-6 py-2.5 bg-amber-600 text-white font-semibold rounded-xl hover:bg-amber-700 transition-all shadow-sm hover:shadow-md">
                💾 Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- Script Dinamis Dropdown --}}
<script>
    const wilayah = @json($wilayahData);
    const kecSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');
    const kabSelect = document.getElementById('kabupaten');

    function populateKecamatan() {
        const kab = kabSelect.value;
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (wilayah[kab]) {
            Object.keys(wilayah[kab]).forEach(kec => {
                const opt = document.createElement('option');
                opt.value = kec;
                opt.text = kec;
                if (kec === "{{ old('kecamatan', $user->kecamatan) }}") opt.selected = true;
                kecSelect.appendChild(opt);
            });
        }
    }

    function populateDesa() {
        const kab = kabSelect.value;
        const kec = kecSelect.value;
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (wilayah[kab] && wilayah[kab][kec]) {
            wilayah[kab][kec].forEach(desa => {
                const opt = document.createElement('option');
                opt.value = desa;
                opt.text = desa;
                if (desa === "{{ old('desa', $user->desa) }}") opt.selected = true;
                desaSelect.appendChild(opt);
            });
        }
    }

    kabSelect.addEventListener('change', populateKecamatan);
    kecSelect.addEventListener('change', populateDesa);

    // Auto isi dropdown saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', () => {
        populateKecamatan();
        populateDesa();
    });
</script>
@endsection
