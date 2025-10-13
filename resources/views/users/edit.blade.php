@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Edit Pengguna</h2>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Role</label>
            <select name="role" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password Baru (opsional)</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Update Pengguna
        </button>
    </form>
</div>
@endsection
