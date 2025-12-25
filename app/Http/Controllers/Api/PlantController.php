<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant;

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

    /**
 * 1. CREATE (Tambah Tanaman Baru)
 */
public function store(Request $request)
{
    // A. Validasi
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|unique:plants,name',
        'latin_name' => 'required|string',
        'family' => 'required|string',
        'part_used' => 'required|string',
        'keywords' => 'required|string',
        'side_effects' => 'nullable|string',
        // Gambar wajib ada saat create
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        'description' => 'required|string',
        'benefits' => 'required|string',
        'processing' => 'required|string',
    ], [
        'name.unique' => 'Tanaman dengan nama ini sudah terdaftar di database',
        'name.required' => 'Nama tanaman wajib diisi',
        'latin_name.required' => 'Nama latin wajib diisi',
        'image.required' => 'Gambar tanaman wajib diunggah',
        'image.image' => 'File harus berupa gambar',
        'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
        'image.max' => 'Ukuran gambar maksimal 2MB',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // B. Upload Gambar
    $image = $request->file('image');
    // Bikin nama file unik: jahe-merah-12345.jpg
    $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
    // Simpan ke folder: storage/app/public/plants
    $image->storeAs('public/plants', $imageName);

    // C. Simpan ke Database
    $plant = Plant::create([
        'name' => $request->name,
        'latin_name' => $request->latin_name,
        'family' => $request->family,
        'part_used' => $request->part_used,
        'image_path' => $imageName, // Simpan nama filenya saja
        'description' => $request->description,
        'benefits' => $request->benefits,
        'processing' => $request->processing,
        'side_effects' => $request->side_effects ?? null,
        'keywords' => $request->keywords ?? '',
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Tanaman berhasil ditambahkan',
        'data' => $plant
    ], 201);
}

/**
 * 2. UPDATE (Edit Tanaman)
 */
public function update(Request $request, $id)
{
    $plant = Plant::find($id);
    if (!$plant) return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);

    // Validasi (Gambar boleh kosong kalau tidak mau diganti)
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'latin_name' => 'required|string',
        'family' => 'required|string',
        'part_used' => 'required|string',
        'keywords' => 'required|string',
        'side_effects' => 'nullable|string',
        'description' => 'required|string',
        'benefits' => 'required|string',
        'processing' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Cek apakah user upload gambar baru?
    if ($request->hasFile('image')) {
        // 1. Hapus gambar lama biar server gak penuh
        if ($plant->image_path) {
            Storage::delete('public/plants/' . $plant->image_path);
        }

        // 2. Upload gambar baru
        $image = $request->file('image');
        $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/plants', $imageName);
        
        // Update nama file di object plant
        $plant->image_path = $imageName;
    }

    // Update data teks
    $plant->update([
        'name' => $request->name,
        'latin_name' => $request->latin_name,
        'family' => $request->family,
        'part_used' => $request->part_used,
        'description' => $request->description,
        'benefits' => $request->benefits,
        'processing' => $request->processing,
        'side_effects' => $request->side_effects,
        'keywords' => $request->keywords,
        'image_path' => $plant->image_path, // Simpan perubahan image_path jika ada
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Data tanaman diperbarui',
        'data' => $plant
    ]);
}

/**
 * 3. DELETE (Hapus Tanaman)
 */
public function destroy($id)
{
    $plant = Plant::find($id);
    if (!$plant) return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);

    // 1. Hapus File Gambar Fisik
    if ($plant->image_path) {
        Storage::delete('public/plants/' . $plant->image_path);
    }

    // 2. Hapus Data di Database
    $plant->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Tanaman berhasil dihapus'
    ]);
}
}
