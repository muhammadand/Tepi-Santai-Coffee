<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-cover bg-center min-h-screen" style="background-image: url('{{ asset('images/bg.jpeg') }}')">


    <div class="flex h-screen bg-amber-100 bg-opacity-90 text-stone-800">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed top-0 left-0 z-30 h-full w-64 transform bg-amber-700 text-amber-50 shadow-xl transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex md:flex-col md:rounded-xl p-4">

            <div class="flex items-center justify-between mb-6 md:hidden">
                <h5 class="text-xl font-semibold text-blue-gray-900">Admin</h5>
                <button id="sidebarCloseBtn" aria-label="Close sidebar"
                    class="text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded">
                    ✕
                </button>
            </div>
            <nav class="flex flex-col gap-1 font-sans text-base text-blue-gray-700">
                @auth
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 p-3 rounded-lg transition-colors 
            text-white hover:text-orange-500 
            hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100"fill="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.25 2.25a.75.75 0 000 1.5H3v10.5a3 3 0 003 3h1.21l-1.172 3.513a.75.75 0 001.424.474l.329-.987h8.418l.33.987a.75.75 0 001.422-.474l-1.17-3.513H18a3 3 0 003-3V3.75h.75a.75.75 0 000-1.5H2.25zm6.04 16.5l.5-1.5h6.42l.5 1.5H8.29zm7.46-12a.75.75 0 00-1.5 0v6a.75.75 0 001.5 0v-6zm-3 2.25a.75.75 0 00-1.5 0v3.75a.75.75 0 001.5 0V9zm-3 2.25a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5z" />
                    </svg>
                    Dashboard
                </a>
              
                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-4 p-3 rounded-lg transition-colors 
                        text-white hover:text-orange-500 
                        hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" />
                        </svg>
                        Produk
                    </a>
            
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-4 p-3 rounded-lg transition-colors 
                        text-white hover:text-orange-500 
                        hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.912 3a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H6.912zm13.823 9.75l-2.213-7.191A1.5 1.5 0 0017.088 4.5H6.912a1.5 1.5 0 00-1.434 1.059L3.265 12.75H6.11a3 3 0 012.684 1.658l.256.513a1.5 1.5 0 001.342.829h3.218a1.5 1.5 0 001.342-.83l.256-.512a3 3 0 012.684-1.658h2.844z" />
                        </svg>
                        Kategori
                    </a>
                    <a href="{{ route('users.index') }}"
                    class="flex items-center gap-4 p-3 rounded-lg transition-colors 
              text-white hover:text-orange-500 
              hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 11a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 00-3-3.87" />
                    </svg>
                    Pelanggan
                </a>
                <a href="{{ route('feedback.index') }}"
                    class="flex items-center gap-4 p-3 rounded-lg transition-colors 
              text-white hover:text-orange-500 
              hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.8-4.2A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Feedback
                </a>
                <a href="{{ route('testimoni.index') }}"
                    class="flex items-center gap-4 p-3 rounded-lg transition-colors 
              text-white hover:text-orange-500 
              hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.8-4.2A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Testimoni produk
                </a>
                <a href="{{ route('laporan.index') }}"
                class="flex items-center gap-4 p-3 rounded-lg transition-colors 
          text-white hover:text-orange-500 
          hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m4 2v-4m4 2v-6M3 7h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Laporan
            </a>
                @endif
            @endauth
            
            
         
                <audio id="order-sound" src="{{ asset('sound/pesanan_masuk.mp3') }}"></audio>


                <a href="{{ route('orders.index') }}"
                    class="flex items-center gap-4 p-3 rounded-lg transition-colors 
                     text-white hover:text-orange-500 
                     hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.912 3a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H6.912zm13.823 9.75l-2.213-7.191A1.5 1.5 0 0017.088 4.5H6.912a1.5 1.5 0 00-1.434 1.059L3.265 12.75H6.11a3 3 0 012.684 1.658l.256.513a1.5 1.5 0 001.342.829h3.218a1.5 1.5 0 001.342-.83l.256-.512a3 3 0 012.684-1.658h2.844z" />
                    </svg>

                    {{-- Nama menu dinamis --}}
                    @if (Auth::check() && Auth::user()->role === 'kasir')
                        Kelola Pesanan
                    @else
                        Pesanan
                    @endif

                    <span id="order-count"
                        class="ml-auto px-2 py-0.5 text-xs font-bold uppercase rounded-full bg-blue-200 text-blue-800 select-none">
                        0
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-4 p-3 rounded-lg transition-colors 
                text-white hover:text-orange-500 
                hover:bg-pink-100 focus:bg-pink-100 focus:text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-grey-100" fill="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16 13v-2H7V8l-5 4 5 4v-3h9zM19 3h-8v2h8v14h-8v2h8a2 2 0 002-2V5a2 2 0 00-2-2z" />
                        </svg>
                        Keluar
                    </button>
                </form>


                <script>
                    let lastCount = null;

                    async function fetchOrderCount() {
                        try {
                            const response = await fetch("{{ route('orders.countPending') }}");
                            const data = await response.json();

                            const countElement = document.getElementById('order-count');
                            const currentCount = parseInt(countElement.textContent);

                            // Jika angka berubah, update dan play suara
                            if (lastCount !== null && data.count !== lastCount) {
                                const audio = document.getElementById('order-sound');
                                audio.pause();
                                audio.currentTime = 0;
                                audio.play().catch(err => {
                                    console.warn("Tidak bisa memutar suara:", err);
                                });
                            }

                            lastCount = data.count;
                            countElement.textContent = data.count;

                        } catch (error) {
                            console.error('Gagal mengambil data count order:', error);
                        }
                    }

                    // Panggil pertama kali saat load halaman
                    fetchOrderCount();

                    // Update setiap 5 detik (5000 ms)
                    setInterval(fetchOrderCount, 5000);
                </script>



            </nav>
        </aside>

        <!-- Content -->
        <div class="flex flex-col flex-1 md:pl-50">
            <header class="sticky top-0 z-20 flex items-center justify-between gap-4 bg-white p-4 shadow-md md:hidden">
                <button id="sidebarOpenBtn" aria-label="Open sidebar"
                    class="text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded">
                    ☰
                </button>
                <h1 class="text-lg font-semibold text-gray-900">Dashboard</h1>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')

            </main>
        </div>

        <script>
            const sidebar = document.getElementById('sidebar');
            const sidebarOpenBtn = document.getElementById('sidebarOpenBtn');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

            sidebarOpenBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });

            sidebarCloseBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });

            // Initially hide sidebar on mobile
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        </script>
    </div>



</body>

</html>
