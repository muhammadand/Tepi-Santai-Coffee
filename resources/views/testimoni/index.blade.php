@extends('layouts.admin')

@section('content')
  <!-- Hero Section -->
  <div class="relative hero-bg h-56 sm:h-72 flex items-center justify-center shadow-md rounded-b-xl">
    <div class="text-center">
      <h1 class="text-3xl sm:text-4xl font-bold text-amber-700">💬 Testimoni Pelanggan</h1>
      <p class="text-sm text-gray-600 mt-2">Cerita mereka yang sudah menikmati rasa Tepi Santai ☕</p>
    </div>
  </div>

  <!-- Testimoni Section -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    @if ($testimonis->isEmpty())
      <div class="text-center py-20">
        <i class="fas fa-comment-slash text-5xl text-amber-300 mb-4"></i>
        <p class="text-gray-500 text-lg">Belum ada testimoni saat ini 😅</p>
        <p class="text-sm text-gray-400">Jadilah yang pertama berbagi pengalamanmu!</p>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($testimonis as $testimoni)
          <div class="collection-card card-hover p-6 rounded-2xl shadow-lg border border-amber-100">
            <!-- User Info -->
            <div class="flex items-center space-x-4 mb-4">
              <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center font-bold">
                {{ strtoupper(substr($testimoni->user->name ?? 'U', 0, 1)) }}
              </div>
              <div>
                <p class="font-semibold text-gray-800">{{ $testimoni->user->name ?? 'Anonim' }}</p>
                <p class="text-xs text-gray-500">Member sejak {{ $testimoni->user->created_at->format('Y') ?? '-' }}</p>
              </div>
            </div>

            <!-- Rating -->
            <div class="flex items-center mb-3">
              @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $testimoni->rating)
                  <i class="fas fa-star text-amber-500"></i>
                @else
                  <i class="far fa-star text-gray-300"></i>
                @endif
              @endfor
            </div>

            <!-- Content -->
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              "{{ $testimoni->content }}"
            </p>

            <!-- Product Info -->
            @if ($testimoni->product)
              <div class="flex items-center justify-between border-t border-amber-100 pt-3">
                <p class="text-sm text-gray-600">
                  <i class="fas fa-mug-saucer text-amber-400 mr-1"></i>
                  <span class="font-medium">{{ $testimoni->product->name }}</span>
                </p>
                <p class="text-xs text-gray-400">{{ $testimoni->created_at->format('d M Y') }}</p>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </section>
@endsection
