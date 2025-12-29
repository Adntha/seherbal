<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();

            // identitas tanaman
            $table->string('name')->unique();
            $table->string('slug')->unique(); // Tambah kolom slug untuk SEO
            $table->string('latin_name');
            $table->string('family');
            $table->string('part_used');

            // data visual & pencarian (frontend)
            $table->string('image_path');
            $table->longText('description');
            $table->string('keywords')->nullable();

            // data detail (untuk chatbot AI dan halaman detail)
            $table->longText('benefits');
            $table->longText('processing');
            $table->text('side_effects')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
