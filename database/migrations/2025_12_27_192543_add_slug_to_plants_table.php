<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Plant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slug untuk data yang sudah ada
        $plants = Plant::all();
        foreach ($plants as $plant) {
            $plant->slug = Str::slug($plant->name);
            $plant->save();
        }

        // Setelah semua data punya slug, buat kolom slug jadi unique dan not null
        Schema::table('plants', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
