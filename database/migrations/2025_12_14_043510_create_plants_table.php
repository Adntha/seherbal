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
        Schema::create('plant', function (Blueprint $table) {
            $table->id();

            // identitas tanaman
            $table->string('name');
            $table->string('latin_name');
            $table->string('family');
            $table->string('part_used');

            // data visual & pencarian (frontend)
            $table->string('description');
            $table->string('keywords');
            $table->string('image_path');

            // data detail (untuk chatbot AI dan halaman detail)
            $table->longText('benefits');
            $table->longText('processing');
            $table->text('side_effects');

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
