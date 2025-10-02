<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'status',
        'skor_akhir',
        'submitted_at',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function itemChecklists(): HasMany
    {
        return $this->hasMany(ItemChecklist::class);
    }
}
