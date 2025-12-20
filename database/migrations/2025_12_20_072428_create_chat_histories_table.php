<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            // Kolom untuk menyimpan pertanyaan user
            $table->text('user_question'); 
            
            // Kolom untuk menyimpan jawaban AI (pakai longText biar muat banyak)
            $table->longText('ai_response'); 
            
            // (Opsional) Jika ingin tahu siapa yang bertanya (bisa null/kosong)
            // $table->foreignId('user_id')->nullable()->constrained(); 
            
            $table->timestamps(); // Otomatis buat created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('chat_histories');
    }
};
