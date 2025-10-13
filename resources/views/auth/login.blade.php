<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Tepi Santai Coffee</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-amber-50 text-gray-800">

  <div class="w-full max-w-md rounded-2xl shadow-2xl p-8 bg-white/10 backdrop-blur-lg border border-amber-200/20">
    <h2 class="text-3xl font-bold mb-6 text-center text-amber-500">Tepi Santai Coffee</h2>
    <p class="text-center text-sm mb-6 text-amber-400">Masuk ke akun Anda untuk menikmati layanan kami</p>

    @if(session('success'))
      <div class="bg-green-600/20 text-green-300 px-4 py-2 mb-4 rounded-md text-sm text-center">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm text-amber-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
          class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
          placeholder="you@example.com">
        @error('email')
          <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm text-amber-700 mb-1">Kata Sandi</label>
        <input type="password" name="password" required
          class="w-full px-4 py-2 bg-white/10 border border-amber-300 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 text-amber-300 placeholder-amber-200"
          placeholder="********">
        @error('password')
          <p class="text-amber-300 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit"
        class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-full font-semibold transition shadow-lg">
        Masuk
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-amber-400">
      Belum punya akun?
      <a href="{{ route('register.user') }}" class="text-amber-500 hover:text-amber-700 font-semibold">Daftar sekarang</a>
    </p>
  </div>

</body>
</html>
