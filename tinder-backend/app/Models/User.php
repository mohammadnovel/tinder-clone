<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles; // Tambahkan ini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pictures' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Swipes made by this user
     */
    public function swipes(): HasMany
    {
        return $this->hasMany(Swipe::class, 'swiper_id');
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
