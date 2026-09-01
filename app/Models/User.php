<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function creatorProfile(): HasOne { return $this->hasOne(CreatorProfile::class); }
    public function posts(): HasMany { return $this->hasMany(Post::class, 'creator_id'); }
    public function sentMessages(): HasMany { return $this->hasMany(Message::class, 'sender_id'); }
    public function receivedMessages(): HasMany { return $this->hasMany(Message::class, 'recipient_id'); }
    public function subscriptions(): HasMany { return $this->hasMany(Subscription::class, 'subscriber_id'); }
    public function canCreateContent(): bool { return $this->role === 'creator' && $this->is_active; }
}
