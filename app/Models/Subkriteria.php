<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kriteria_id',
        'nama_subkriteria',
        'deskripsi_subkriteria',
    ];

    /**
     * Get the parent criterion.
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    /**
     * Get the indicators for the sub-criterion.
     */
    public function indikators()
    {
        return $this->hasMany(Indikator::class, 'subkriteria_id');
    }
}