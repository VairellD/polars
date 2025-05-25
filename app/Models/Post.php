<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_url', // Tetap ada untuk backward compatibility
        'file_type', // Tetap ada untuk backward compatibility
        'file_extension', // Tetap ada untuk backward compatibility
        'file_size', // Tetap ada untuk backward compatibility
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for the post.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the media for this post.
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Check if user has liked this post
     * FIXED: Now handles null users (guests)
     */
    public function isLikedBy(?User $user = null): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}