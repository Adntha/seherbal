<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file JSON
        $jsonPath = database_path('data/dataset_tanaman_herbal.json');

        // Cek apakah file ada
        if (!file_exists($jsonPath)) {
            $this->command->error("File JSON tidak ditemukan: {$jsonPath}");
            return;
        }

        // Baca file JSON
        $jsonContent = file_get_contents($jsonPath);
        $plants = json_decode($jsonContent, true);

        // Cek apakah JSON valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("Error parsing JSON: " . json_last_error_msg());
            return;
        }

        $this->command->info("Memproses " . count($plants) . " data tanaman...");

        // Loop dan insert ke database
        foreach ($plants as $plantData) {
            // Skip jika Status_Publikasi false
            if (!$plantData['Status_Publikasi']) {
                continue;
            }

            // Mapping dari struktur JSON ke struktur database
            Plant::create([
                'name' => $plantData['Nama_Lokal'],
                'latin_name' => $plantData['Nama_Ilmiah_Latin'],
                'family' => $plantData['Kategori_Famili'],
                'part_used' => $plantData['Bagian_Digunakan'],
                'description' => $plantData['Ringkasan_Snippet'],
                'keywords' => implode(', ', $plantData['Keywords_Synonyms']), // Array to string
                'image_path' => strtolower(str_replace(' ', '_', $plantData['Nama_Lokal'])) . '.jpg', // Generate image filename
                'benefits' => $plantData['Khasiat_Utama'],
                'processing' => $plantData['Detail_Penggunaan'],
                'side_effects' => $plantData['Peringatan_Efek_Samping'],
            ]);
        }

        $this->command->info("Seeding selesai!");
    }
}
