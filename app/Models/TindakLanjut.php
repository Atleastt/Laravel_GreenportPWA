<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjuts';

    protected $fillable = [
        'rekomendasi_id',
        'auditee_id',
        'rencana_tindakan',
        'target_penyelesaian',
        'penanggung_jawab',
        'sumber_daya_dibutuhkan',
        'status_progres',
        'persentase_penyelesaian',
        'catatan_progres',
        'bukti_penyelesaian',
        'milestones',
        'tanggal_update_terakhir'
    ];

    protected $casts = [
        'target_penyelesaian' => 'date',
        'tanggal_update_terakhir' => 'datetime',
        'milestones' => 'array'
    ];

    /**
     * Get the rekomendasi that owns the tindak lanjut.
     */
    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class);
    }

    /**
     * Get the auditee that owns the tindak lanjut.
     */
    public function auditee()
    {
        return $this->belongsTo(User::class, 'auditee_id');
    }

    /**
     * Get progress percentage as formatted string
     */
    public function getProgressPercentageAttribute()
    {
        return $this->persentase_penyelesaian ?? 0;
    }

    /**
     * Check if tindak lanjut is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->target_penyelesaian < now() && $this->status_progres !== 'Selesai';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status_progres) {
            'Direncanakan' => 'gray',
            'Sedang Berjalan' => 'blue',
            'Hampir Selesai' => 'yellow',
            'Selesai' => 'green',
            'Terhambat' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get completed milestones count
     */
    public function getCompletedMilestonesCountAttribute()
    {
        $milestones = $this->milestones ?? [];
        return collect($milestones)->where('status', 'Selesai')->count();
    }

    /**
     * Get total milestones count
     */
    public function getTotalMilestonesCountAttribute()
    {
        return count($this->milestones ?? []);
    }
}
