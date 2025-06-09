<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'posts_count',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'posts_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Automatically generate slug when creating category
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (is_null($category->is_active)) {
                $category->is_active = true;
            }
            if (is_null($category->sort_order)) {
                $category->sort_order = static::max('sort_order') + 1;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get posts in this category (one-to-many - backward compatibility)
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get posts that have this category (many-to-many)
     */
    public function postsMany()
    {
        return $this->belongsToMany(Post::class, 'post_categories');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Update posts count (backward compatibility)
     */
    public function updatePostsCount()
    {
        $this->posts_count = $this->posts()->count();
        $this->save();
    }

    /**
     * Update posts count for many-to-many relationship
     */
    public function updatePostsCountMany()
    {
        $this->posts_count = $this->postsMany()->count();
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
