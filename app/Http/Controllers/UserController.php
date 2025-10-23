<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan form edit profil user yang sedang login
     */
   public function edit()
{
    // Ambil user yang sedang login
    $user = Auth::user();

    // Baca file JSON wilayah Kuningan dari storage
    $path = storage_path('app/data/wilayah_kuningan.json');
    $wilayahData = [];

    if (file_exists($path)) {
        $json = file_get_contents($path);
        $wilayahData = json_decode($json, true);
    }

    // Kirim data ke view
    return view('profil.edit', compact('user', 'wilayahData'));
}

    /**
     * Update data profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'kabupaten'     => 'nullable|string|max:255',
            'kecamatan'     => 'nullable|string|max:255',
            'desa'          => 'nullable|string|max:255',
            'detail_alamat' => 'nullable|string|max:255',
            'password'      => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'kabupaten', 'kecamatan', 'desa', 'detail_alamat']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
