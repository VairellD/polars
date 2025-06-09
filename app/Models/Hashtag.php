<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'posts_count',
    ];

    protected $casts = [
        'posts_count' => 'integer',
    ];

    /**
     * Automatically generate slug when creating hashtag
     */
    protected static function booted()
    {
        static::creating(function ($hashtag) {
            if (empty($hashtag->slug)) {
                $hashtag->slug = Str::slug($hashtag->name);
            }
        });

        static::updating(function ($hashtag) {
            if ($hashtag->isDirty('name')) {
                $hashtag->slug = Str::slug($hashtag->name);
            }
        });
    }

    /**
     * Get posts that have this hashtag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_hashtags');
    }

    /**
     * Get or create hashtag by name
     */
    public static function findOrCreateByName($name)
    {
        $cleanName = strtolower(trim($name, '#'));

        return static::firstOrCreate(
            ['name' => $cleanName],
            ['slug' => Str::slug($cleanName)]
        );
    }

    /**
     * Get trending hashtags
     */
    public static function trending($limit = 10)
    {
        return static::where('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Update posts count
     */
    public function updatePostsCount()
    {
        $this->posts_count = $this->posts()->count();
        $this->save();
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
