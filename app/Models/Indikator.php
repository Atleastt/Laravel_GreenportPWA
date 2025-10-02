<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subkriteria_id',
        'teks_indikator',
        'bobot',
        'poin',
        'tipe_jawaban',
    ];

    /**
     * Get the parent sub-criterion.
     */
    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }

    /**
     * The audits that this indicator is part of.
     */
    public function audits()
    {
        return $this->belongsToMany(Audit::class, 'audit_criteria', 'criterion_id', 'audit_id')
                    ->using(AuditCriterion::class)
                    ->withPivot('score', 'auditor_notes', 'status')
                    ->withTimestamps();
    }

    /**
     * Get the documents for the indicator.
     */
    public function itemChecklists(): HasMany
    {
        return $this->hasMany(ItemChecklist::class);
    }


}
