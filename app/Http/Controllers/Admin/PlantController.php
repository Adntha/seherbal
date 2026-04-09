<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PlantController extends Controller
{
    // --- Menampilkan halaman Edit ---
    public function edit($id)
    {
        $plant = Plant::findOrFail($id);
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
            'name'       => 'required|string|max:255|unique:plants,name',
            'latin_name' => 'required|string|max:255',
            'description' => 'nullable',
            'benefits'   => 'nullable',
            'usage'      => 'nullable',
            'image'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.unique'      => 'Tanaman dengan nama ini sudah ada di database. Silakan gunakan nama lain.',
            'name.required'    => 'Nama tanaman wajib diisi.',
            'latin_name.required' => 'Nama latin wajib diisi.',
            'image.required'   => 'Gambar tanaman wajib diunggah.',
            'image.image'      => 'File harus berupa gambar.',
            'image.mimes'      => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max'        => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Upload ke Cloudinary
        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadedFile = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'seherbal/plants']
            );
            $imagePath = $uploadedFile->getSecurePath();
        }

        Plant::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'latin_name'  => $request->latin_name,
            'family'      => $request->family ?? 'Tidak Diketahui',
            'part_used'   => $request->part_used ?? 'Tidak Diketahui',
            'description' => $request->description,
            'keywords'    => $request->keywords ?? '',
            'benefits'    => $request->benefits,
            'processing'  => $request->usage ?? 'Konsultasikan dengan ahli',
            'side_effects' => $request->side_effects ?? null,
            'image_path'  => $imagePath,
        ]);

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Tanaman berhasil ditambahkan!');
    }

    // Update data yang sudah ada
    public function update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:plants,name,' . $id,
            'latin_name'  => 'nullable|string|max:255',
            'family'      => 'nullable|string|max:255',
            'part_used'   => 'nullable|string|max:255',
            'keywords'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'processing'  => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique'   => 'Tanaman dengan nama ini sudah ada di database.',
            'name.required' => 'Nama tanaman wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            // Upload gambar baru ke Cloudinary
            $uploadedFile = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'seherbal/plants']
            );
            $plant->image_path = $uploadedFile->getSecurePath();
        }

        $plant->name        = $request->name;
        $plant->slug        = Str::slug($request->name);
        $plant->latin_name  = $request->latin_name  ?? $plant->latin_name;
        $plant->family      = $request->family      ?? $plant->family;
        $plant->part_used   = $request->part_used   ?? $plant->part_used;
        $plant->description = $request->description ?? $plant->description;
        $plant->keywords    = $request->keywords    ?? $plant->keywords;
        $plant->benefits    = $request->benefits    ?? $plant->benefits;
        $plant->processing  = $request->processing  ?? $plant->processing;
        $plant->side_effects = $request->side_effects ?? $plant->side_effects;
        $plant->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Tanaman berhasil diperbarui',
            'data'    => $plant,
        ]);
    }
}