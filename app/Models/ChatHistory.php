<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi data
    protected $fillable = [
        'user_question',
        'ai_response'
    ];
}
