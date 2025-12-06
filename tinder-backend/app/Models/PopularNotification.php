<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopularNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likes_count',
        'admin_email',
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'likes_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
