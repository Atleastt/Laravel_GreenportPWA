<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'auditor_id',
        'auditee_id',
        'scheduled_start_date',
        'scheduled_end_date',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'scheduled_start_date' => 'date',
        'scheduled_end_date'   => 'date',
    ];

    /**
     * Get the auditor for this audit.
     */
    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    /**
     * Get the auditee for this audit.
     */
    public function auditee()
    {
        return $this->belongsTo(User::class, 'auditee_id');
    }

    /**
     * The criteria (indicators) for this audit.
     */
    public function checklistAudits()
    {
        return $this->hasMany(ChecklistAudit::class);
    }

    public function criteria()
    {
        return $this->belongsToMany(Indikator::class, 'audit_criteria', 'audit_id', 'criterion_id')
                    ->using(AuditCriterion::class)
                    ->withPivot('score', 'auditor_notes', 'status', 'auditee_notes', 'auditee_attachment_path')
                    ->withTimestamps();
    }

    /**
     * Get the laporan for the audit.
     */
    public function laporan()
    {
        return $this->hasOne(Laporan::class);
    }
}
