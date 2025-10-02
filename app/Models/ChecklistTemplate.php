<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\ChecklistKepatuhan;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama_template',
        'deskripsi_template',
        'pembuat_auditor_id',
    ];

    /**
     * Relationship: audit checklists using this template.
     */
    public function auditChecklists(): HasMany
    {
        return $this->hasMany(ChecklistKepatuhan::class);
    }

    /**
     * Relationship: pembuat auditor.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_auditor_id');
    }
}
