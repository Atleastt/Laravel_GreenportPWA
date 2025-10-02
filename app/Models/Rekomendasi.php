<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rekomendasi extends Model
{
    protected $fillable = [
        'audit_id',
        'auditor_id',
        'kategori',
        'deskripsi_temuan',
        'rekomendasi_perbaikan',
        'batas_waktu',
        'prioritas',
        'status',
        'catatan_tindak_lanjut',
        'tanggal_tindak_lanjut',
        'bukti_perbaikan_path'
    ];

    protected $casts = [
        'batas_waktu' => 'date',
        'tanggal_tindak_lanjut' => 'datetime'
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}
