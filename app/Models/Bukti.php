<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Temuan;

class Bukti extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buktis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'temuan_id',
        'pengguna_unggah_id',
        'nama_dokumen',
        'file_path',
        'status',
        'verified_by_user_id',
        'auditor_feedback',
        'uploaded_at',
    ];

    /**
     * Get the user who uploaded the document.
     */
    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'pengguna_unggah_id');
    }

    /**
     * Get the user who verified the document.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Get the specific audit criterion this document belongs to.
     */
    // public function auditCriterion(): BelongsTo
//    {
//        return $this->belongsTo(AuditCriterion::class, 'audit_criterion_id');
//    }

    /**
     * Get the temuan that this bukti belongs to.
     */
    public function temuan(): BelongsTo
    {
        return $this->belongsTo(Temuan::class);
    }
}
