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
        'category_id',
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
     * Get the category of the post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Many-to-many categories relationship (ADD THIS - MISSING)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }


    /**
     * Get the hashtags for the post.
     */
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags');
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

    /**
     * Extract and sync hashtags from description
     */
    public function syncHashtagsFromDescription()
    {
        $hashtags = [];

        if ($this->description) {
            // Extract hashtags using regex
            preg_match_all('/#([a-zA-Z0-9_]+)/', $this->description, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $hashtagName) {
                    $hashtag = Hashtag::findOrCreateByName($hashtagName);
                    $hashtags[] = $hashtag->id;
                }
            }
        }

        // Sync hashtags
        $this->hashtags()->sync($hashtags);

        // Update hashtag post counts
        foreach ($this->hashtags as $hashtag) {
            $hashtag->updatePostsCount();
        }
    }

    /**
     * Get hashtag names as array
     */
    public function getHashtagNamesAttribute()
    {
        return $this->hashtags->pluck('name')->toArray();
    }

    /**
     * Get formatted description with clickable hashtags
     */
    public function getFormattedDescriptionAttribute()
    {
        if (!$this->description) {
            return '';
        }

        return preg_replace(
            '/#([a-zA-Z0-9_]+)/',
            '<a href="/hashtag/$1" class="hashtag-link">#$1</a>',
            $this->description
        );
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        // Automatically sync hashtags when post is saved
        static::saved(function ($post) {
            $post->syncHashtagsFromDescription();
        });

        // Update category post count when post is deleted
        static::deleted(function ($post) {
            if ($post->category) {
                $post->category->updatePostsCount();
            }

            // Update hashtag counts
            foreach ($post->hashtags as $hashtag) {
                $hashtag->updatePostsCount();
            }
        });
    }
}
