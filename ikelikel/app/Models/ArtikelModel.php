<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';

    protected $fillable = [
        'judul',
        'konten',
        'penulis',
        'tanggal_publish',
        'kategori',
        'created_at',
        'updated_at',
    ];
}
