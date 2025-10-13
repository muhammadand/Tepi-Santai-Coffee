<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title ?? 'Tepi Santai Coffee' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer>
    function toggleMenu() {
      const nav = document.getElementById('mobile-menu');
      nav.classList.toggle('hidden');
    }
  </script>
  <!-- CDN Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      font-family: 'Poppins', sans-serif;
    }

    .hero-bg {
      background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 50%, #fff7ed 100%);
    }

    .donut-shadow {
      filter: drop-shadow(0 10px 20px rgba(245, 158, 11, 0.2));
    }

    .card-hover {
      transition: all 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(245, 158, 11, 0.15);
    }

    .amber-gradient {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .collection-card {
      background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,247,237,0.8));
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Header -->
  <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between py-4">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center">
            <i class="fas fa-mug-saucer text-white text-lg"></i>
          </div>
          <div>
            <h1 class="text-xl font-bold text-amber-600">Tepi Santai</h1>
            <p class="text-xs text-amber-400 -mt-1">Coffee</p>
          </div>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-8">
          <a href="{{url('/')}}" class="text-gray-700 hover:text-amber-500 transition font-medium">Home</a>
          <a href="{{route('menu.index')}}" class="text-gray-700 hover:text-amber-500 transition font-medium">Shop</a>
          <a href="{{route('orders.user')}}" class="text-gray-700 hover:text-amber-500 transition font-medium">My Orders</a>
        </nav>

        <!-- Social & Actions -->
        <div class="flex items-center space-x-4">
          <div class="hidden md:flex space-x-3">
            <i class="fab fa-facebook text-gray-400 hover:text-amber-500 cursor-pointer transition"></i>
            <i class="fab fa-twitter text-gray-400 hover:text-amber-500 cursor-pointer transition"></i>
            <i class="fab fa-instagram text-gray-400 hover:text-amber-500 cursor-pointer transition"></i>
          </div>
          @guest
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-amber-500 transition">
              <i class="far fa-user"></i> Log in
            </a>
          @endguest

          @auth
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="text-gray-600 hover:text-amber-500 transition bg-transparent border-none cursor-pointer">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          @endauth
        </div>
      </div>
    </div>
  </header>

  

  <!-- Main Content -->
  <main class="min-h-screen">
    @yield('content')
  </main>
    <!-- Footer -->
<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center">
              <i class="fas fa-mug-saucer text-white text-sm"></i>
            </div>
            <div>
              <h1 class="text-lg font-bold text-amber-400">Tepi Santai Coffee</h1>
            </div>
          </div>
          <p class="text-gray-400 text-sm">
            Menyajikan kopi dan camilan berkualitas dengan suasana santai di tepi kota.
          </p>
        </div>
        
        <div>
          <h4 class="font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-amber-400 transition">Home</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">About</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">Shop</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">Contact</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="font-semibold mb-4">Categories</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-amber-400 transition">New Arrivals</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">Best Sellers</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">Seasonal</a></li>
            <li><a href="#" class="hover:text-amber-400 transition">Sale</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="font-semibold mb-4">Follow Us</h4>
          <div class="flex space-x-4">
            <i class="fab fa-facebook text-gray-400 hover:text-amber-400 cursor-pointer transition text-lg"></i>
            <i class="fab fa-twitter text-gray-400 hover:text-amber-400 cursor-pointer transition text-lg"></i>
            <i class="fab fa-instagram text-gray-400 hover:text-amber-400 cursor-pointer transition text-lg"></i>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
        <p>&copy; 2025 Tepi Santai Coffee. All rights reserved.</p>
      </div>
    </div>
  </footer>
  

</body>
</html>
