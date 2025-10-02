<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor untuk full_name, mapping ke name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get audits where user is auditor.
     */
    public function auditsAsAuditor()
    {
        return $this->hasMany(Audit::class, 'auditor_id');
    }

    /**
     * Get audits where user is auditee.
     */
    public function auditsAsAuditee()
    {
        return $this->hasMany(Audit::class, 'auditee_id');
    }

    /**
     * Get rekomendasi created by this auditor.
     */
    public function rekomendasisAsAuditor()
    {
        return $this->hasMany(Rekomendasi::class, 'auditor_id');
    }

    /**
     * Get rekomendasi assigned to this auditee.
     */
    public function rekomendasisAsAuditee()
    {
        return $this->hasMany(Rekomendasi::class, 'auditee_id');
    }

    /**
     * Get tindak lanjut by this auditee.
     */
    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class, 'auditee_id');
    }
}
