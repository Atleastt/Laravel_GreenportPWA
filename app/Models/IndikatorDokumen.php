<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorDokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_indikator',
        'deskripsi',
        'kategori',
        'file_path',
        'mime_type',
        'size',
        'uploaded_by',
    ];
}
