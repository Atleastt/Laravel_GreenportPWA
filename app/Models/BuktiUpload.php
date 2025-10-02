<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_checklist_id',
        'uploader_id',
        'nama_file_original',
        'path_file_di_server',
        'tipe_file',
        'ukuran_file',
        'status_upload',
        'checksum_sha256',
    ];

    public function itemChecklist(): BelongsTo
    {
        return $this->belongsTo(ItemChecklist::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
