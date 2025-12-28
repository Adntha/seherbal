<?php

namespace App\Http\Controllers;

use App\Models\Plant; 
use Illuminate\Http\Request;

class TanamanController extends Controller
{
    // Ini tetap untuk halaman depan (welcome) - JANGAN DIUBAH
    public function index()
    {
        $tanaman = \App\Models\Plant::take(10)->get(); 
        return view('welcome', compact('tanaman'));
    }

    // Ini tetap untuk API chatbot/search - JANGAN DIUBAH
    public function loadAll() {
        return response()->json(\App\Models\Plant::all());
    }

    public function show($slug)
    {
    // Cari tanaman berdasarkan ID atau berikan error 404 jika tidak ada
    $tanaman = \App\Models\Plant::where('slug', $slug)->firstOrFail();
    
    return view('tanaman-detail', compact('tanaman'));
    }

    // --- TAMBAHKAN FUNGSI BARU DI BAWAH INI UNTUK DASHBOARD ---
    public function adminIndex()
    {
        // Mengambil semua data tanaman untuk tabel dashboard
        $tanaman = Plant::all(); 
        
        // Menghitung statistik berdasarkan data di database
        $totalTanaman = $tanaman->count();
        // Asumsi ada kolom 'status' di tabel plants kamu
        // $totalDraft = Plant::where('status', 'draft')->count();
        $totalDraft = 0;

        return view('admin.dashboard', compact('tanaman', 'totalTanaman', 'totalDraft'));
    }
}