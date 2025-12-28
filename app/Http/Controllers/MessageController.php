<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Mengambil pesan terbaru
        $pesan = Message::latest()->get();
        $totalPesan = $pesan->count();
        $pesanBaru = Message::where('is_read', false)->count();

        return view('admin.messages', compact('pesan', 'totalPesan', 'pesanBaru'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string|max:5000',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'subjek.required' => 'Subjek wajib diisi',
            'pesan.required' => 'Pesan wajib diisi',
        ]);

        // Simpan ke database
        Message::create($validated);

        // Return JSON response untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
        ]);
    }

    public function destroy($id)
    {
        Message::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus');
    }
}