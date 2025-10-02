<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kriteria',
        'deskripsi_kriteria',
    ];

    /**
     * Get the sub-criteria for the criterion.
     */
    public function subkriterias()
    {
        return $this->hasMany(Subkriteria::class);
    }

}
