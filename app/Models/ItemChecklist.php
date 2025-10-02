<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_audit_id',
        'indikator_id',
        'jawaban_teks',
        'jawaban_skala',
        'jawaban_ya_tidak',
        'komentar_auditor',
        'skor_final',
    ];

    public function checklistAudit(): BelongsTo
    {
        return $this->belongsTo(ChecklistAudit::class);
    }

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class);
    }

    public function buktiUploads(): HasMany
    {
        return $this->hasMany(BuktiUpload::class);
    }
}
