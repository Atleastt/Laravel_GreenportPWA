<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuditCriterion extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_criteria';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function criterion()
    {
        // Assuming 'criterion_id' links to the 'indikator' table based on our previous setup
        return $this->belongsTo(Indikator::class, 'criterion_id');
    }

    public function documents()
    {
        return $this->hasMany(Bukti::class, 'audit_criterion_id');
    }
}
