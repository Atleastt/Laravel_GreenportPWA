<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'content'
    ];

    public function discussion()
    {
        return $this->belongsTo(ForumDiscussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
