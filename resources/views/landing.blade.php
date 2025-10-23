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
                @php
                    $canDiscount = false;
                    if (Auth::check() && is_array($product->discount_user_ids)) {
                        $canDiscount = in_array(Auth::id(), $product->discount_user_ids);
                    }
                @endphp

                <div class="bg-white rounded-2xl p-6 card-hover shadow-lg border border-amber-100 flex flex-col">
                    <div class="h-48 bg-amber-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('storage/' . $product->foto) }}" 
                             alt="{{ $product->name }}" 
                             class="object-cover h-full w-full transition-transform duration-300 hover:scale-105">
                    </div>

                    <div class="flex-grow">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-500 italic mb-3">
                            {{ $product->category->name ?? 'Tidak ada kategori' }}
                        </p>

                        {{-- 💰 Logika harga & diskon --}}
                        @if ($product->discount_active && $product->discount && $canDiscount)
                            <p class="text-sm line-through text-gray-400">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-amber-500 font-bold mb-2">
                                Rp {{ number_format($product->price - $product->discount, 0, ',', '.') }}
                            </p>
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded inline-block">
                                Diskon Spesial
                            </span>
                        @else
                            <p class="text-amber-500 font-bold mb-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    {{-- Tombol Add to Cart --}}
                    <button
                        onclick="addToCart({{ $product->id }})"
                        class="mt-4 flex items-center justify-center gap-2 w-full px-4 py-2 rounded-full bg-amber-500 text-white hover:bg-amber-600 transition-all duration-300 shadow-md hover:shadow-lg"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6h13.4M7 13H5.4M16 17a2 2 0 11-4 0m6 0a2 2 0 11-4 0" />
                        </svg>
                        + cart
                    </button>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Produk tidak ditemukan.
                </p>
            @endforelse
        </div>
    </div>
</section>

<!-- Tombol Toggle Keranjang Desktop (Ikon + Tengah Kanan) -->
<button id="toggleCartBtn" onclick="toggleCart()"
    class="hidden md:flex fixed top-1/2 right-0 transform -translate-y-1/2 
           bg-amber-600 text-white p-3 rounded-l-xl shadow-lg 
           cursor-pointer z-50 text-xl hover:bg-amber-700 transition"
    title="Keranjang">
    <i class="fa-solid fa-cart-shopping"></i>
</button>

<!-- Keranjang Desktop -->
<div 
    id="cart" 
    class="fixed top-16 right-5 w-80 max-h-[70vh] bg-white border border-amber-200 rounded-2xl p-4 shadow-xl overflow-y-auto hidden z-[9999]"
>
    <!-- Header -->
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-bold text-amber-600">Keranjang Pesanan</h3>
        <button onclick="toggleCart()" class="text-gray-500 text-xl hover:text-amber-500 transition">&times;</button>
    </div>

    <!-- Isi Keranjang -->
    <ul id="cart-items" class="space-y-2 text-sm text-gray-700 list-none p-0 m-0"></ul>

    <!-- Keranjang Kosong -->
    <p id="cart-empty" class="text-center text-gray-400 mt-4">Keranjang kosong</p>

    <!-- Tombol Checkout -->
    <a 
        href="{{ url('/cart') }}" 
        id="checkoutBtn" 
        class="mt-4 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 rounded-full text-center block transition duration-300 hidden"
    >
        Checkout
    </a>
</div>

<!-- Keranjang Mobile (Floating Summary) -->
<div id="cartMobileSummary" 
    onclick="toggleCartMobile()"
    class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-amber-600 text-white px-6 py-3 rounded-full shadow-lg font-semibold text-sm flex items-center justify-center gap-2 max-w-[90vw] cursor-pointer z-[9999] hidden"
>
    <i class="fas fa-shopping-cart"></i>
    <span id="cartMobileText">Keranjang kosong</span>
</div>

<!-- Keranjang Mobile Expanded -->
<div id="cartMobileExpanded" style="
    position:fixed; bottom:70px; left:50%; transform:translateX(-50%);
    width:90vw; max-width:400px;
    max-height:60vh;
    background:#fff; border:1px solid #ddd; border-radius:10px;
    box-shadow:0 3px 12px rgba(0,0,0,0.25);
    padding:15px;
    overflow-y:auto;
    display:none;
    z-index:9999;
    font-size:14px;
    color:#333;
">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <h3 style="margin:0; font-weight:700;">Keranjang Pesanan</h3>
        <button onclick="toggleCartMobile()" 
            style="background:none; border:none; font-size:20px; cursor:pointer; color:#555;">
            &times;
        </button>
    </div>
    <ul id="cart-items-mobile" style="list-style:none; padding-left:0; margin:0;"></ul>
    <p id="cart-empty-mobile" style="color:#888; text-align:center; margin-top:20px;">Keranjang kosong</p>
    <a href="{{ url('/cart') }}" id="checkoutBtnMobile" style="
        display:none;
        margin-top:15px; 
        background:#22863a; color:#fff; 
        text-align:center; padding:10px 0; 
        border-radius:6px; font-weight:600; 
        text-decoration:none;
        display:block;
    ">Checkout</a>
</div>

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
function isMobile() {
    return window.innerWidth <= 768;
}

function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
    })
    .then(res => res.json())
    .then(data => {
        if (isMobile()) {
            showCartMobile(data.cart);
        } else {
            showCart(data.cart);
        }
    })
    .catch(console.error);
}

function updateQty(productId, change) {
    fetch(`/cart/update/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ change: change })
    })
    .then(res => res.json())
    .then(data => {
        if (isMobile()) {
            showCartMobile(data.cart);
        } else {
            showCart(data.cart);
        }
    })
    .catch(console.error);
}

// Desktop cart
function showCart(cart) {
    const cartEl = document.getElementById('cart');
    const itemsEl = document.getElementById('cart-items');
    const emptyEl = document.getElementById('cart-empty');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const toggleBtn = document.getElementById('toggleCartBtn');

    if (!cart || Object.keys(cart).length === 0) {
        itemsEl.innerHTML = '';
        emptyEl.style.display = 'block';
        checkoutBtn.style.display = 'none';
        cartEl.style.display = 'none';
        toggleBtn.style.display = 'block';
        return;
    }

    let total = 0;
    itemsEl.innerHTML = Object.entries(cart).map(([id, item]) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        return `
            <li style="padding:8px 0; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>${item.name}</strong><br>
                    <small>Rp ${item.price.toLocaleString('id-ID')}</small>
                </div>
                <div style="display:flex; align-items:center; gap:5px;">
                    <button onclick="updateQty(${id}, -1)" style="border:1px solid #ccc; background:#f7f7f7; padding:2px 6px; cursor:pointer;">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQty(${id}, 1)" style="border:1px solid #ccc; background:#f7f7f7; padding:2px 6px; cursor:pointer;">+</button>
                </div>
            </li>`;
    }).join('');

    itemsEl.innerHTML += `
        <li style="padding:10px 0; font-weight:700; text-align:right;">
            Total: Rp ${total.toLocaleString('id-ID')}
        </li>
    `;

    emptyEl.style.display = 'none';
    checkoutBtn.style.display = 'block';
    cartEl.style.display = 'block';
    toggleBtn.style.display = 'block';
}

function toggleCart() {
    const cartEl = document.getElementById('cart');
    cartEl.style.display = cartEl.style.display === 'block' ? 'none' : 'block';
}

// Mobile cart
function showCartMobile(cart) {
    const summaryEl = document.getElementById('cartMobileSummary');
    const expandedEl = document.getElementById('cartMobileExpanded');
    const itemsEl = document.getElementById('cart-items-mobile');
    const emptyEl = document.getElementById('cart-empty-mobile');
    const checkoutBtn = document.getElementById('checkoutBtnMobile');

    if (!cart || Object.keys(cart).length === 0) {
        summaryEl.innerHTML = '<i class="fas fa-shopping-cart"></i> <span>Keranjang kosong</span>';
        itemsEl.innerHTML = '';
        emptyEl.style.display = 'block';
        checkoutBtn.style.display = 'none';
        summaryEl.style.display = 'flex';
        expandedEl.style.display = 'none';
        return;
    }

    let totalQty = 0;
    let totalPrice = 0;

    itemsEl.innerHTML = Object.entries(cart).map(([id, item]) => {
        totalQty += item.quantity;
        totalPrice += item.price * item.quantity;
        return `
            <li style="padding:8px 0; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>${item.name}</strong><br>
                    <small>Rp ${item.price.toLocaleString('id-ID')}</small>
                </div>
                <div style="display:flex; align-items:center; gap:5px;">
                    <button onclick="updateQty(${id}, -1)" style="border:1px solid #ccc; background:#f7f7f7; padding:2px 6px; cursor:pointer;">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQty(${id}, 1)" style="border:1px solid #ccc; background:#f7f7f7; padding:2px 6px; cursor:pointer;">+</button>
                </div>
            </li>`;
    }).join('');

    itemsEl.innerHTML += `
        <li style="padding:10px 0; font-weight:700; text-align:right;">
            Total: Rp ${totalPrice.toLocaleString('id-ID')}
        </li>
    `;

    summaryEl.innerHTML = `<i class="fas fa-shopping-cart"></i> <span>${totalQty} item - Rp ${totalPrice.toLocaleString('id-ID')}</span>`;
    emptyEl.style.display = 'none';
    checkoutBtn.style.display = 'block';
    summaryEl.style.display = 'flex';
}

function toggleCartMobile() {
    const expandedEl = document.getElementById('cartMobileExpanded');
    expandedEl.style.display = expandedEl.style.display === 'block' ? 'none' : 'block';
}

// Inisialisasi tampilan sesuai device
function initCartUI() {
    if (isMobile()) {
        document.getElementById('toggleCartBtn').style.display = 'none';
        document.getElementById('cart').style.display = 'none';
        document.getElementById('cartMobileSummary').style.display = 'flex';
        document.getElementById('cartMobileExpanded').style.display = 'none';
    } else {
        document.getElementById('toggleCartBtn').style.display = 'flex';
        document.getElementById('cart').style.display = 'none';
        document.getElementById('cartMobileSummary').style.display = 'none';
        document.getElementById('cartMobileExpanded').style.display = 'none';
    }
}

// Load keranjang awal dari server
window.addEventListener('load', () => {
    fetch('/cart')
    .then(res => res.json())
    .then(data => {
        if (isMobile()) {
            showCartMobile(data.cart);
        } else {
            showCart(data.cart);
        }
        initCartUI();
    })
    .catch(() => {
        initCartUI();
    });
});

// Update UI saat resize
window.addEventListener('resize', () => {
    initCartUI();
});

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