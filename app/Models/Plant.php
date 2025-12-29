<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // Tambah slug untuk mass assignment
        'latin_name',
        'family',
        'part_used',
        'description',
        'keywords',
        'image_path',
        'benefits',
        'processing',
        'side_effects',
    ];
}
