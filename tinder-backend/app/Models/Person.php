<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Person extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'pictures',
        'location',
        'bio',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pictures' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Swipes received by this person
     */
    public function swipesReceived(): HasMany
    {
        return $this->hasMany(Swipe::class, 'swiped_id');
    }

    /**
     * Get likes received count
     */
    public function getLikesCountAttribute(): int
    {
        return $this->swipesReceived()->where('type', 'like')->count();
    }

    /**
     * Check if this person is popular (50+ likes)
     */
    public function isPopular(): bool
    {
        return $this->likes_count >= 50;
    }

    /**
     * Scope to get people not yet swiped by a user
     */
    public function scopeNotSwipedBy(Builder $query, int $userId): Builder
    {
        return $query->whereNotIn('id', function ($q) use ($userId) {
            $q->select('swiped_id')
                ->from('swipes')
                ->where('swiper_id', $userId);
        });
    }

    /**
     * Scope to order by distance from coordinates
     */
    public function scopeNearby(Builder $query, float $lat, float $lng): Builder
    {
        return $query->selectRaw("*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", 
            [$lat, $lng, $lat]
        )->orderBy('distance');
    }

    /**
     * Calculate distance from given coordinates
     */
    public function distanceFrom(float $lat, float $lng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        $earthRadius = 6371; // km

        $latDiff = deg2rad($this->latitude - $lat);
        $lngDiff = deg2rad($this->longitude - $lng);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat)) * cos(deg2rad($this->latitude)) *
            sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }
}
