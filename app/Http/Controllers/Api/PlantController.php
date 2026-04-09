<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if ($query) {
            $plants = Plant::where('name', 'like', "%{$query}%")
                ->orWhere('benefits', 'like', "%{$query}%")
                ->orWhere('keywords', 'like', "%{$query}%")
                ->get();
        } else {
            $plants = Plant::all();
        }

        // image_path sudah berupa URL Cloudinary penuh, langsung gunakan
        $plants->transform(function ($plant) {
            $plant->image_url = $plant->image_path;
            return $plant;
        });

        return response()->json([
            'status' => 'success',
            'total'  => $plants->count(),
            'data'   => $plants,
        ]);
    }

    public function show($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return response()->json(['status' => 'error', 'message' => 'Tanaman tidak ditemukan'], 404);
        }

        $plant->image_url = $plant->image_path;

        return response()->json([
            'status' => 'success',
            'data'   => $plant,
        ]);
    }

    /**
     * CREATE - Tambah Tanaman Baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|unique:plants,name|max:255',
            'latin_name'  => 'required|string|max:255',
            'family'      => 'required|string|max:255',
            'part_used'   => 'required|string|max:255',
            'keywords'    => 'required|string',
            'side_effects' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'benefits'    => 'required|string',
            'processing'  => 'required|string',
        ], [
            'name.unique'        => 'Tanaman dengan nama ini sudah terdaftar di database',
            'name.required'      => 'Nama tanaman wajib diisi',
            'latin_name.required' => 'Nama latin wajib diisi',
            'image.required'     => 'Gambar tanaman wajib diunggah',
            'image.image'        => 'File harus berupa gambar',
            'image.mimes'        => 'Format gambar harus jpeg, png, atau jpg',
            'image.max'          => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Upload ke Cloudinary
        $uploadedFile = Cloudinary::upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'seherbal/plants']
        );
        $imagePath = $uploadedFile->getSecurePath();

        $plant = Plant::create([
            'name'        => $request->name,
            'latin_name'  => $request->latin_name,
            'family'      => $request->family,
            'part_used'   => $request->part_used,
            'image_path'  => $imagePath,
            'description' => $request->description,
            'benefits'    => $request->benefits,
            'processing'  => $request->processing,
            'side_effects' => $request->side_effects ?? null,
            'keywords'    => $request->keywords ?? '',
            'slug'        => Str::slug($request->name),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tanaman berhasil ditambahkan',
            'data'    => $plant,
        ], 201);
    }

    /**
     * UPDATE - Edit Tanaman
     */
    public function update(Request $request, $id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tanaman tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'latin_name'  => 'nullable|string|max:255',
            'family'      => 'nullable|string|max:255',
            'part_used'   => 'nullable|string|max:255',
            'keywords'    => 'nullable|string',
            'side_effects' => 'nullable|string',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'processing'  => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Upload gambar baru ke Cloudinary
            $uploadedFile = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'seherbal/plants']
            );
            $plant->image_path = $uploadedFile->getSecurePath();
        }

        $plant->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'latin_name'  => $request->latin_name,
            'family'      => $request->family,
            'part_used'   => $request->part_used,
            'description' => $request->description,
            'benefits'    => $request->benefits,
            'processing'  => $request->processing,
            'side_effects' => $request->side_effects,
            'keywords'    => $request->keywords,
            'image_path'  => $plant->image_path,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data tanaman berhasil diperbarui',
            'data'    => $plant,
        ]);
    }

    /**
     * DELETE - Hapus Tanaman
     */
    public function destroy($id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tanaman tidak ditemukan',
            ], 404);
        }

        $plant->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Tanaman berhasil dihapus',
        ]);
    }
}
