<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil kata kunci pencarian (jika ada)
        $query = $request->input('q');

        if ($query) {
            // Logika Pencarian Cerdas (Cari di Nama, Khasiat, atau Keyword)
            $plants = Plant::where('name', 'like', "%{$query}%")
                ->orWhere('benefits', 'like', "%{$query}%")
                ->orWhere('keywords', 'like', "%{$query}%")
                ->get();
        } else {
            // Jika tidak cari apa-apa, ambil semua data
            $plants = Plant::all();
        }

        // 2. Modifikasi data agar URL gambar bisa langsung dibuka
        // Mengubah "jahe.jpg" jadi "http://localhost:8000/storage/plants/jahe.jpg"
        $plants->transform(function ($plant) {
            $plant->image_url = asset('storage/plants/' . $plant->image_path);
            return $plant;
        });

        // 3. Kirim format JSON
        return response()->json([
            'status' => 'success',
            'total' => $plants->count(),
            'data' => $plants
        ]);
    }

    public function show($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return response()->json(['status' => 'error', 'message' => 'Tanaman tidak ditemukan'], 404);
        }

        // Lengkapi URL gambar untuk detail juga
        $plant->image_url = asset('storage/plants/' . $plant->image_path);

        return response()->json([
            'status' => 'success',
            'data' => $plant
        ]);
    }
}
