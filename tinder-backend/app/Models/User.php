<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'pictures',
        'location',
        'bio',
        'latitude',
        'longitude',
        'is_blocked',  // TAMBAHAN
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pictures' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_blocked' => 'boolean',  // TAMBAHAN
    ];

    /**
     * Swipes made by this user
     */
    public function swipes(): HasMany
    {
        return $this->hasMany(Swipe::class, 'swiper_id');
    }

    /**
     * Swipes received by this user (TAMBAHAN)
     */
    public function receivedSwipes(): HasMany
    {
        return $this->hasMany(Swipe::class, 'swiped_id');
    }

    /**
     * Popular notifications for this user (TAMBAHAN)
     */
    public function popularNotifications(): HasMany
    {
        return $this->hasMany(PopularNotification::class);
    }

    /**
     * Get IDs of people this user has swiped
     */
    public function getSwipedPeopleIds(): array
    {
        return $this->swipes()->pluck('swiped_id')->toArray();
    }

    /**
     * Get IDs of people this user has liked
     */
    public function getLikedPeopleIds(): array
    {
        return $this->swipes()->where('type', 'like')->pluck('swiped_id')->toArray();
    }

    /**
     * Get IDs of people this user has disliked
     */
    public function getDislikedPeopleIds(): array
    {
        return $this->swipes()->where('type', 'dislike')->pluck('swiped_id')->toArray();
    }
}
