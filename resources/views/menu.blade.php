@extends('layouts.app')

@section('content')
@if(session('error'))
    <div style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif

<!-- Mini Hero Banner -->
<section class="bg-amber-100 rounded-2xl px-6 py-8 mb-6 shadow-sm border border-amber-200">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <!-- Text -->
        <div class="flex-1 text-center md:text-left">
            <h2 class="text-2xl md:text-3xl font-bold text-amber-600 mb-2">
                Selamat Datang di <span class="text-amber-500">Tepi Santai Coffee</span> ☕
            </h2>
            <p class="text-sm md:text-base text-gray-700">
                Nikmati kopi hangat, camilan lezat, dan suasana santai untuk setiap momen spesialmu!
            </p>
        </div>

        <!-- Gambar Mini -->
        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-amber-200 shadow-md">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv54o4Aju4ZPpMHbo_RowyNFTB8UqmzTYsDw&s" 
                 alt="Kopi Spesial" 
                 class="w-full h-full object-cover" />
        </div>
    </div>
</section>





<section class="py-10 max-w-6xl mx-auto px-4">


    <form method="GET" action="{{ route('menu.index') }}" class="mb-8">
        <div class="flex flex-wrap gap-3 items-center">
            <!-- Input Pencarian -->
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari kopi atau camilan..." 
                class="flex-1 min-w-0 px-4 py-2 rounded-full border border-amber-200 bg-white text-gray-700 shadow-sm focus:ring-2 focus:ring-amber-300 focus:outline-none transition"
            />
    
            <!-- Dropdown Kategori -->
            <select 
                name="category_id" 
                class="flex-1 min-w-0 px-4 py-2 rounded-full border border-amber-200 bg-white text-gray-700 shadow-sm focus:ring-2 focus:ring-amber-300 focus:outline-none transition"
            >
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
    
            <!-- Tombol Cari -->
            <button 
                type="submit" 
                class="px-6 py-2 rounded-full bg-amber-500 text-white font-semibold hover:bg-amber-600 transition shadow-md hover:shadow-lg"
            >
                Cari
            </button>
        </div>
    </form>
    
    
    
<!-- Grid Produk -->
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse ($products as $product)
        @php
            $canDiscount = false;
            if (Auth::check() && is_array($product->discount_user_ids)) {
                $canDiscount = in_array(Auth::id(), $product->discount_user_ids);
            }
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden hover:shadow-md transition-transform hover:scale-[1.02] flex flex-col">
            <!-- Gambar Produk -->
            <div class="w-full aspect-square overflow-hidden">
                <img src="{{ asset('storage/' . $product->foto) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
            </div>
            
            <!-- Info Produk -->
            <div class="p-4 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-amber-600 text-base sm:text-lg">{{ $product->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 italic">
                        {{ $product->category->name ?? 'Tidak ada kategori' }}
                    </p>

                    {{-- 💰 Logika harga & diskon --}}
                    @if ($product->discount_active && $product->discount && $canDiscount)
                        {{-- Harga normal dicoret --}}
                        <p class="mt-2 text-sm line-through text-gray-400">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Harga setelah diskon --}}
                        <p class="mt-1 font-bold text-amber-500 text-sm sm:text-base">
                            Rp {{ number_format($product->price - $product->discount, 0, ',', '.') }}
                        </p>

                        <span class="text-[11px] text-green-600 bg-green-100 px-2 py-0.5 rounded">
                            Diskon Spesial untuk Anda
                        </span>
                    @else
                        <p class="mt-2 font-bold text-amber-500 text-sm sm:text-base">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    @endif
                </div>

                <button
                    onclick="addToCart({{ $product->id }})"
                    class="mt-4 flex items-center justify-center gap-2 px-3 py-2 rounded-full bg-amber-100 text-amber-700 border border-amber-300 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-md"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6h13.4M7 13H5.4M16 17a2 2 0 11-4 0m6 0a2 2 0 11-4 0" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
    @empty
        <p class="col-span-full text-center text-gray-400 italic">Produk tidak ditemukan.</p>
    @endforelse
</div>



<!-- Pagination -->
<div class="mt-8 flex justify-center">
    {{ $products->withQueryString()->links() }}
</div>


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
        summaryEl.textContent = 'Keranjang kosong';
        itemsEl.innerHTML = '';
        emptyEl.style.display = 'block';
        checkoutBtn.style.display = 'none';
        summaryEl.style.display = 'block';
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

    summaryEl.textContent = `${totalQty} item - Rp ${totalPrice.toLocaleString('id-ID')}`;
    emptyEl.style.display = 'none';
    checkoutBtn.style.display = 'block';
    summaryEl.style.display = 'block';
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
        document.getElementById('cartMobileSummary').style.display = 'block';
        document.getElementById('cartMobileExpanded').style.display = 'none';
    } else {
        document.getElementById('toggleCartBtn').style.display = 'block';
        document.getElementById('cart').style.display = 'none';
        document.getElementById('cartMobileSummary').style.display = 'none';
        document.getElementById('cartMobileExpanded').style.display = 'none';
    }
}

// Load keranjang awal dari server (opsional)
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

// Update UI saat resize (opsional)
window.addEventListener('resize', () => {
    initCartUI();
});
</script>



@endsection
