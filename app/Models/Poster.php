<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'judul',
        'narasi',
        'gambar',
        'frame',
        'hasil_final',
        'scale_gambar',
        'pos_x',
        'pos_y',
        'judul_narasi_gap',
        'judul_y',
        'narasi_y',
    ];
}