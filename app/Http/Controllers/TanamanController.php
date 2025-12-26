<?php

namespace App\Http\Controllers;

// 1. Ganti Tanaman menjadi Plant agar sesuai dengan Seeder
use App\Models\Plant; 
use Illuminate\Http\Request;

class TanamanController extends Controller
{
    public function index()
    {
        // Gunakan take(10) untuk membatasi hanya 10 data pertama
        $tanaman = \App\Models\Plant::take(10)->get(); 
        return view('welcome', compact('tanaman'));
    }

    // Tambahkan fungsi ini di TanamanController.php
    public function loadAll() {
        return response()->json(\App\Models\Plant::all());
        
}
}