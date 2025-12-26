<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk menentukan nama tabel secara manual
    protected $table = 'tanaman'; 

    protected $guarded = [];
}