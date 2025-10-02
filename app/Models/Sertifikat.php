<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'certificate_number',
        'issued_by',
        'compliance_score',
        'valid_until',
        'status'
    ];

    protected $casts = [
        'valid_until' => 'date',
        'compliance_score' => 'decimal:2'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isValid()
    {
        return $this->status === 'Active' && $this->valid_until >= now();
    }
}
