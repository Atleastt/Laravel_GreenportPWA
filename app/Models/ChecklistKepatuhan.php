<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Audit;
use App\Models\ChecklistTemplate;
use App\Models\ItemChecklist;

class ChecklistKepatuhan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'audit_id',
        'checklist_template_id',
        'status',
        'tanggal_submit_auditee',
    ];

    /**
     * Relationship: belongs to audit.
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Relationship: belongs to template.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    /**
     * Relationship: item checklists.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ItemChecklist::class);
    }
}
