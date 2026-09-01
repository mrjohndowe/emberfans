<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'display_name', 'bio', 'avatar_path', 'banner_path', 'monthly_price', 'is_verified', 'is_accepting_tips'];
    protected function casts(): array { return ['monthly_price' => 'decimal:2', 'is_verified' => 'boolean', 'is_accepting_tips' => 'boolean']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function posts(): HasMany { return $this->hasMany(Post::class, 'creator_id', 'user_id'); }
}
