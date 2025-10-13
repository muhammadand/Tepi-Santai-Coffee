@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px; margin-bottom:15px;">
            {{ session('error') }}
        </div>
    @endif

<!-- Hero Section -->
<section class="py-20 overflow-hidden bg-amber-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center">
            <!-- Hero Text -->
            <div class="lg:w-1/2 text-center lg:text-left mb-10 lg:mb-0">
                <h2 class="text-5xl lg:text-6xl font-bold text-gray-800 mb-6 leading-tight">
                    Nikmati <span class="text-amber-500">Kopi dan Camilan</span><br>
                    Di Tepi Santai Coffee!
                </h2>
                <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto lg:mx-0">
                    Rasakan suasana santai sambil menikmati kopi spesial dan camilan homemade yang hangat dan lezat. Pesan sekarang dan buat harimu lebih berkesan!
                </p>

                <a href="{{ route('menu.index') }}"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-4 rounded-full font-semibold transition-transform hover:scale-105 shadow-lg">
                    Lihat Menu
                </a>
            </div>

            <!-- Hero Image -->
            <div class="lg:w-1/2 flex justify-center lg:justify-end">
                <div class="relative w-80 h-80 rounded-full overflow-hidden shadow-lg border-4 border-amber-100">
                    <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=800&q=80"
                        alt="Kopi dan Camilan" class="object-cover w-full h-full">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Best Sellers / Rekomendasi -->
<section class="py-20 bg-amber-50">
    <div class="container mx-auto px-4">
        <h3 class="text-4xl font-bold text-center text-gray-800 mb-16">
            {{ Auth::check() ? 'Rekomendasi untuk Anda' : 'Best Sellers Terbaru' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($products as $product)
                <div class="bg-white rounded-2xl p-6 card-hover shadow-lg border border-amber-100">
                    <div class="h-48 bg-amber-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('storage/' . $product->foto) }}" 
                             alt="{{ $product->name }}" 
                             class="object-cover h-full w-full">
                    </div>

                    <h4 class="font-semibold text-gray-800 mb-2">{{ $product->name }}</h4>

                    <p class="text-amber-500 font-bold">
                        @if ($product->discount_active && $product->discount)
                            <span class="line-through text-gray-400 mr-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span>
                                Rp {{ number_format($product->price - $product->discount, 0, ',', '.') }}
                            </span>
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Produk tidak ditemukan.
                </p>
            @endforelse
        </div>
    </div>
</section>


<!-- Mission / Feedback Section -->
<section class="py-20 bg-amber-50 relative">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center">
            <!-- Text & Button -->
            <div class="lg:w-1/2 mb-10 lg:mb-0">
                <h3 class="text-4xl font-bold text-gray-800 mb-6">Berikan Feedback</h3>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Kami sangat menghargai masukan dari Anda. Klik tombol di bawah untuk mengisi formulir feedback.
                </p>
                <button onclick="toggleFeedbackForm()"
                    class="bg-amber-500 text-white px-8 py-4 rounded-full font-semibold hover:scale-105 transition-transform shadow-lg">
                    Feedback
                </button>
            </div>

            <!-- Gambar / Ilustrasi -->
            <div class="lg:w-1/2 flex justify-center lg:justify-end">
                <div class="relative">
                    <div
                        class="w-80 h-80 bg-gradient-to-br from-amber-200 to-amber-300 rounded-full flex items-center justify-center">
                        <div class="w-64 h-64 bg-white rounded-full flex items-center justify-center shadow-xl">
                            <div class="text-center">
                                <div
                                    class="w-32 h-32 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-4xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-amber-500">Best!</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulir Feedback (Hidden by default) -->
        <div id="feedbackForm"
            class="mt-12 bg-white shadow-lg rounded-2xl p-6 max-w-2xl mx-auto hidden transition-all duration-500">
            <h4 class="text-xl font-bold text-gray-800 mb-4 text-center">Form Feedback</h4>
            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-4">
                @csrf
                @auth
                    <input type="hidden" name="nama" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                @else
                    <input type="text" name="nama" placeholder="Nama" required
                        class="w-full border border-amber-200 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300 outline-none" />
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full border border-amber-200 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300 outline-none" />
                @endauth

                <textarea name="pesan" rows="4" placeholder="Tulis pesan Anda..." required
                    class="w-full border border-amber-200 rounded-2xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-300 outline-none"></textarea>

                <select name="rating" required
                    class="w-full border border-amber-200 rounded-full px-4 py-2 text-sm text-gray-600 focus:ring-2 focus:ring-amber-300 outline-none">
                    <option value="">Pilih Rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} Bintang</option>
                    @endfor
                </select>

                <div class="text-center">
                    <button type="submit"
                        class="bg-amber-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-amber-600 transition shadow-lg">
                        Kirim Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function toggleFeedbackForm() {
        const form = document.getElementById('feedbackForm');
        form.classList.toggle('hidden');
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#f59e0b', // amber-500
    });
</script>
@endif



@endsection
