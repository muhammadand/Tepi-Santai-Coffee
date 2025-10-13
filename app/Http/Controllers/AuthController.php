<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function index()
    {
        $users = User::where('role', 'pelanggan')
            ->withCount('orders') // Hitung jumlah order
            ->get();

        return view('users.index', compact('users'));
    }




    // Tampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function registeruser()
    {
        return view('auth.user.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|string',
            'password' => 'required|string|min:6|confirmed', // pakai confirmed
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Register berhasil, silakan login.');
    }

    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Arahkan sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/dashboard');

                case 'pelanggan':
                    return redirect()->intended('/');

                case 'kasir':
                    return redirect()->intended(route('orders.index'));


                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'email' => 'Role tidak dikenali',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }



    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function create()
    {
        return view('users.create');
    }

    // Simpan Pengguna Baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:user,admin',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Form Edit Pengguna
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update Pengguna
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:user,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Hapus Pengguna
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
