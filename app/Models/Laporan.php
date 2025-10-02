<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'audit_id',
        'executive_summary',
        'findings_recommendations',
        'compliance_score',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'compliance_score' => 'decimal:2',
    ];

    /**
     * Get the audit that owns the laporan.
     */
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
