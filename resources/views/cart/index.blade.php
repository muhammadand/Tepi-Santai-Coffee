@extends('layouts.app')

@section('content')
    <section
        class="mt-10 max-w-6xl mx-auto px-4 sm:px-6 py-10 bg-amber-50 rounded-2xl shadow-xl border border-amber-100 text-gray-900">
        <h2 class="text-2xl sm:text-4xl font-bold text-center text-amber-700 mb-10 tracking-tight">
            ☕ Lengkapi Data Pemesanan
        </h2>

        @php
            $cart = session('cart', []);
            $total = 0;
        @endphp

        @if (count($cart) > 0)
            <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8"
                id="checkoutForm">
                @csrf

                <!-- 🛒 Kiri: Produk -->
                <div class="space-y-5">
                    @foreach ($cart as $id => $item)
                        @php
                            $hasDiscount = isset($item['discount_active']) && $item['discount_active'];
                            $originalPrice = $hasDiscount ? $item['price'] + $item['discount'] : $item['price'];
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp

                        <div
                            class="bg-white border border-amber-200 rounded-xl shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-lg font-semibold text-amber-700">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">Jumlah: <span
                                        class="font-medium">{{ $item['quantity'] }}</span></p>
                            </div>
                            <div class="text-right">
                                @if ($hasDiscount)
                                    <p class="text-sm line-through text-red-400">Rp
                                        {{ number_format($originalPrice, 0, ',', '.') }}</p>
                                    <p class="text-sm font-semibold text-green-600">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm font-medium text-gray-700">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }}</p>
                                @endif
                                <p class="text-base font-bold text-gray-800 mt-1">Subtotal: Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- 📋 Kanan: Form -->
                <div
                    class="bg-white border border-amber-200 rounded-xl shadow-sm p-6 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1 font-medium text-amber-700">Nama Pemesan</label>
                            <input type="text" name="nama" value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                required
                                class="w-full rounded-full border border-amber-200 px-4 py-2 bg-white placeholder-gray-400 focus:ring-2 focus:ring-amber-300 focus:outline-none transition text-sm"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <!-- Tombol Ubah Alamat (jika sudah ada alamat) -->
                        @if($user->kabupaten && $user->kecamatan && $user->desa)
                        <div class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <div class="text-sm text-gray-600">
                                <p class="font-medium text-amber-700">Alamat Tersimpan:</p>
                                <p>{{ $user->desa }}, {{ $user->kecamatan }}</p>
                            </div>
                            <button type="button" id="btnUbahAlamat"
                                class="text-xs bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-full transition">
                                Ubah
                            </button>
                        </div>
                        @endif

                        <!-- Hidden inputs untuk alamat lengkap (akan terisi otomatis) -->
                        <input type="hidden" name="kabupaten" id="kabupatenHidden" value="{{ $user->kabupaten ?? '' }}">
                        <input type="hidden" name="kecamatan" id="kecamatanHidden" value="{{ $user->kecamatan ?? '' }}">
                        <input type="hidden" name="desa" id="desaHidden" value="{{ $user->desa ?? '' }}">

                        <!-- Kabupaten (untuk edit) -->
                        <select id="kabupaten"
                            class="w-full rounded-full border border-amber-200 px-4 py-2 text-sm bg-white"
                            style="{{ ($user->kabupaten && $user->kecamatan && $user->desa) ? 'display:none' : '' }}">
                            <option value="">Pilih Kabupaten</option>
                            <option value="Kuningan" {{ $user->kabupaten == 'Kuningan' ? 'selected' : '' }}>Kuningan</option>
                        </select>

                        <!-- Kecamatan (untuk edit) -->
                        <select id="kecamatan"
                            class="w-full rounded-full border border-amber-200 px-4 py-2 text-sm bg-white"
                            style="{{ ($user->kabupaten && $user->kecamatan && $user->desa) ? 'display:none' : '' }}">
                            <option value="">Pilih Kecamatan</option>
                            @if ($user->kecamatan)
                                <option value="{{ $user->kecamatan }}" selected>{{ $user->kecamatan }}</option>
                            @endif
                        </select>

                        <!-- Desa (untuk edit) -->
                        <select id="desa"
                            class="w-full rounded-full border border-amber-200 px-4 py-2 text-sm bg-white"
                            style="{{ ($user->kabupaten && $user->kecamatan && $user->desa) ? 'display:none' : '' }}">
                            <option value="">Pilih Desa</option>
                            @if ($user->desa)
                                <option value="{{ $user->desa }}" selected>{{ $user->desa }}</option>
                            @endif
                        </select>

                        <!-- Detail Alamat -->
                        <div>
                            <input type="text" name="detail_alamat" id="detail_alamat" required
                                value="{{ $user->detail_alamat ?? '' }}"
                                class="w-full rounded-full border border-amber-200 px-4 py-2 text-sm"
                                placeholder="Masukkan detail alamat (RT/RW, no rumah, dll)"
                                {{ $user->detail_alamat ? 'readonly' : '' }}>
                            @if($user->detail_alamat)
                            <button type="button" id="btnUbahDetail"
                                class="text-xs text-amber-600 hover:text-amber-700 mt-1 ml-2">
                                Ubah detail alamat
                            </button>
                            @endif
                        </div>

                        <input type="hidden" name="payment_method" value="non-tunai">
                        <input type="hidden" name="ongkir" id="ongkirInput" value="0">
                    </div>

                    <!-- Total & Tombol -->
                    <div class="pt-4 border-t border-amber-200">
                        <div
                            class="flex justify-between items-center text-base sm:text-lg font-semibold text-gray-800 mb-2">
                            <span>Subtotal</span>
                            <span id="subtotalText">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center text-base sm:text-lg font-semibold text-gray-800 mb-2">
                            <span>Ongkir</span>
                            <span id="ongkirText">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center font-bold text-lg text-gray-900 mb-4">
                            <span>Total</span>
                            <span class="text-amber-700" id="totalText">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit"
                            class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-full transition shadow-md hover:shadow-lg">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </div>
            </form>
        @else
            <p class="text-center text-gray-500 italic text-base sm:text-lg mt-6">Keranjang Anda kosong ☕</p>
        @endif
    </section>

    <!-- 🔧 Script Wilayah & Ongkir -->
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const kabupatenSelect = document.getElementById('kabupaten');
            const kecamatanSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');
            const kabupatenHidden = document.getElementById('kabupatenHidden');
            const kecamatanHidden = document.getElementById('kecamatanHidden');
            const desaHidden = document.getElementById('desaHidden');
            const detailAlamat = document.getElementById('detail_alamat');
            const ongkirInput = document.getElementById('ongkirInput');
            const ongkirText = document.getElementById('ongkirText');
            const totalText = document.getElementById('totalText');
            const btnUbahAlamat = document.getElementById('btnUbahAlamat');
            const btnUbahDetail = document.getElementById('btnUbahDetail');
            const subtotal = {{ $total }};
            
            const res = await fetch('{{ asset('data/wilayah_kuningan.json') }}');
            const wilayah = await res.json();
            const kabupaten = 'Kuningan';

            // Cek apakah user sudah punya alamat lengkap
            const hasCompleteAddress = {{ $user->kabupaten && $user->kecamatan && $user->desa ? 'true' : 'false' }};

            // Fungsi untuk hitung ongkir
            function hitungOngkir() {
                const ongkir = Math.floor(Math.random() * (15000 - 10000 + 1)) + 10000;
                ongkirInput.value = ongkir;
                ongkirText.textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
                totalText.textContent = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');
            }

            // Fungsi untuk reset ongkir
            function resetOngkir() {
                ongkirInput.value = 0;
                ongkirText.textContent = 'Rp 0';
                totalText.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            }

            // Fungsi untuk setup dropdown
            function setupDropdowns() {
                // Load semua kecamatan
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                Object.keys(wilayah[kabupaten]).forEach(kec => {
                    kecamatanSelect.innerHTML += `<option value="${kec}">${kec}</option>`;
                });

                // Event kecamatan change
                kecamatanSelect.addEventListener('change', () => {
                    const kec = kecamatanSelect.value;
                    desaSelect.innerHTML = '<option value="">Pilih Desa</option>';

                    if (wilayah[kabupaten][kec]) {
                        wilayah[kabupaten][kec].forEach(desa => {
                            desaSelect.innerHTML += `<option value="${desa}">${desa}</option>`;
                        });
                    }
                    
                    // Update hidden input
                    kecamatanHidden.value = kec;
                    desaHidden.value = '';
                    resetOngkir();
                });

                // Event desa change
                desaSelect.addEventListener('change', () => {
                    desaHidden.value = desaSelect.value;
                    if (desaSelect.value) {
                        hitungOngkir();
                    } else {
                        resetOngkir();
                    }
                });

                // Event kabupaten change
                kabupatenSelect.addEventListener('change', () => {
                    kabupatenHidden.value = kabupatenSelect.value;
                });
            }

            // Jika user sudah punya alamat lengkap
            if (hasCompleteAddress) {
                hitungOngkir(); // Langsung hitung ongkir

                // Tombol ubah alamat
                if (btnUbahAlamat) {
                    btnUbahAlamat.addEventListener('click', () => {
                        kabupatenSelect.style.display = 'block';
                        kecamatanSelect.style.display = 'block';
                        desaSelect.style.display = 'block';
                        btnUbahAlamat.style.display = 'none';
                        setupDropdowns();
                        resetOngkir();
                    });
                }

                // Tombol ubah detail alamat
                if (btnUbahDetail) {
                    btnUbahDetail.addEventListener('click', () => {
                        detailAlamat.readOnly = false;
                        detailAlamat.focus();
                        btnUbahDetail.style.display = 'none';
                    });
                }
            } else {
                // User belum punya alamat, enable semua
                setupDropdowns();
            }
        });
    </script>
@endsection