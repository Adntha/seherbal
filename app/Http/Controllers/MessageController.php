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

    public function destroy($id)
    {
        Message::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus');
    }
}