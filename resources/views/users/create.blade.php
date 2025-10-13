@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Tambah Pengguna Baru</h2>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Role</label>
            <select name="role" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Simpan Pengguna
        </button>
    </form>
</div>
@endsection
