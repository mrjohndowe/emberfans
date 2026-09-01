<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['creator_id', 'body', 'media_type', 'media_path', 'thumbnail_path', 'is_locked', 'unlock_price', 'published_at'];
    protected function casts(): array { return ['is_locked' => 'boolean', 'unlock_price' => 'decimal:2', 'published_at' => 'datetime']; }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'creator_id'); }
    public function comments(): HasMany { return $this->hasMany(Comment::class); }
    public function likes(): HasMany { return $this->hasMany(Like::class); }
    public function unlocks(): HasMany { return $this->hasMany(ContentUnlock::class); }
}
