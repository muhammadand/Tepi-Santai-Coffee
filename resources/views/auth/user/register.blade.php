<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - Tepi Santai Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-amber-50 text-gray-800">

    <div class="w-full max-w-md rounded-2xl shadow-2xl p-8 bg-white/10 backdrop-blur-lg border border-amber-200/20">
        <h2 class="text-3xl font-bold mb-6 text-center text-amber-500">Tepi Santai Coffee</h2>
        <p class="text-center text-sm mb-6 text-amber-400">Daftar akun baru untuk menikmati layanan kami</p>

        @if (session('success'))
            <div class="bg-green-600/20 text-green-300 px-4 py-2 mb-4 rounded-md text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-amber-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
                    placeholder="Nama Lengkap">
                @error('name')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-amber-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
                    placeholder="you@example.com">
                @error('email')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dropdown Wilayah --}}
            <div>
                <label class="block text-sm text-amber-700 mb-1">Kabupaten</label>
                <select id="kabupaten" name="kabupaten" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full text-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">-- Pilih Kabupaten --</option>
                </select>
                @error('kabupaten')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-amber-700 mb-1">Kecamatan</label>
                <select id="kecamatan" name="kecamatan" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full text-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">-- Pilih Kecamatan --</option>
                </select>
                @error('kecamatan')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-amber-700 mb-1">Desa</label>
                <select id="desa" name="desa" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full text-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">-- Pilih Desa --</option>
                </select>
                @error('desa')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="role" value="pelanggan">

            <div>
                <label class="block text-sm text-amber-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
                    placeholder="********">
                @error('password')
                    <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-amber-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
                    placeholder="********">
            </div>

            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-full font-semibold transition shadow-lg">
                Daftar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-amber-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-amber-500 hover:text-amber-700 font-semibold">Masuk sekarang</a>
        </p>
    </div>

    {{-- Script Wilayah --}}
    <script>
        const wilayah = @json($wilayah ?? []);

        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');

        // isi kabupaten
        Object.keys(wilayah).forEach(kab => {
            const opt = document.createElement('option');
            opt.value = kab;
            opt.textContent = kab;
            kabSelect.appendChild(opt);
        });

        kabSelect.addEventListener('change', function() {
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

            const selectedKab = this.value;
            if (wilayah[selectedKab]) {
                Object.keys(wilayah[selectedKab]).forEach(kec => {
                    const opt = document.createElement('option');
                    opt.value = kec;
                    opt.textContent = kec;
                    kecSelect.appendChild(opt);
                });
            }
        });

        kecSelect.addEventListener('change', function() {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            const selectedKab = kabSelect.value;
            const selectedKec = this.value;

            if (wilayah[selectedKab] && wilayah[selectedKab][selectedKec]) {
                wilayah[selectedKab][selectedKec].forEach(desa => {
                    const opt = document.createElement('option');
                    opt.value = desa;
                    opt.textContent = desa;
                    desaSelect.appendChild(opt);
                });
            }
        });
    </script>

</body>

</html>
