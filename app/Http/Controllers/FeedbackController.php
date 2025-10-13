<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Tampilkan form feedback
    public function create()
    {
        return view('feedback.create');
    }

    // Simpan feedback ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Feedback::create([
            'user_id' => Auth::id(), // bisa null jika belum login
            'nama' => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas feedback Anda!');
    }

    // (Opsional) Tampilkan semua feedback - hanya untuk admin misalnya
    public function index()
{
    $feedbacks = Feedback::latest()->paginate(10); // atau ->get() kalau tidak mau paginate
    return view('feedback.index', compact('feedbacks'));
}

}
