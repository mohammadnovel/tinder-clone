<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Swipe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'swiper_id',
        'swiped_id',
        'type',
    ];

    /**
     * The user who made the swipe
     */
    public function swiper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'swiper_id');
    }

    /**
     * The person who was swiped
     */
    public function swiped(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'swiped_id');
    }

    /**
     * Scope for likes only
     */
    public function scopeLikes(Builder $query): Builder
    {
        return $query->where('type', 'like');
    }

    /**
     * Scope for dislikes only
     */
    public function scopeDislikes(Builder $query): Builder
    {
        return $query->where('type', 'dislike');
    }

    /**
     * Scope for swipes by a specific user
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('swiper_id', $userId);
    }

    /**
     * Scope for swipes on a specific person
     */
    public function scopeOnPerson(Builder $query, int $personId): Builder
    {
        return $query->where('swiped_id', $personId);
    }
}
