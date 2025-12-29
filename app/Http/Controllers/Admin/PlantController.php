<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    // --- TAMBAHKAN INI: Menampilkan halaman Edit ---
    public function edit($id)
    {
        // Mencari data tanaman, jika tidak ada akan otomatis 404
        $plant = Plant::findOrFail($id);

        // Pastikan path view ini benar: resources/views/admin/plants/edit-tanaman.blade.php
        return view('admin.plants.edit-tanaman', compact('plant'));
    }

    // Menampilkan halaman form tambah
    public function create()
    {
        return view('admin.plants.create');
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latin_name' => 'required|string|max:255',
            'description' => 'nullable',
            'benefits' => 'nullable',
            'usage' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/plants', $imageName);
        }

        Plant::create([
            'name' => $request->name,
            'latin_name' => $request->latin_name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'benefits' => $request->benefits,
            'usage' => $request->usage,
            'image_path' => $imageName, 
        ]);

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Tanaman berhasil ditambahkan!');
    }

    // Update data yang sudah ada
    public function update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'latin_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama menggunakan image_path (sesuai field di store)
            if ($plant->image_path && Storage::exists('public/plants/' . $plant->image_path)) {
                Storage::delete('public/plants/' . $plant->image_path);
            }

            // Simpan foto baru dengan format nama yang sama dengan store()
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/plants', $imageName);
            
            $plant->image_path = $imageName;
        }

        $plant->name = $request->name;
        $plant->latin_name = $request->latin_name;
        $plant->slug = Str::slug($request->name);
        $plant->description = $request->description;
        $plant->save();

        // Karena update dipanggil lewat AJAX/Fetch di JS, kita kembalikan JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Tanaman berhasil diperbarui',
            'data' => $plant
        ]);
    }
}