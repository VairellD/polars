<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Hashtag;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with media gallery using database categories
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryFilter = $request->get('category', 'all');
        $hashtagFilter = $request->get('hashtag', null);

        // Base query for posts with media
        $query = Post::with(['media', 'user', 'category', 'hashtags'])
            ->withCount(['likes', 'comments'])
            ->where(function ($q) {
                // Only get posts that have media or legacy file_url
                $q->whereHas('media')
                    ->orWhereNotNull('file_url');
            });

        // Apply category filter
        if ($categoryFilter !== 'all') {
            $query->where(function ($q) use ($categoryFilter) {
                // Primary category filtering
                $q->whereHas('category', function ($categoryQuery) use ($categoryFilter) {
                    $categoryQuery->where('slug', $categoryFilter);
                })
                    // Or filter by media type for media-based categories
                    ->orWhere(function ($mediaQuery) use ($categoryFilter) {
                        if (in_array($categoryFilter, ['video', 'audio', 'image', 'foto'])) {
                            $mediaType = $categoryFilter === 'foto' ? 'image' : $categoryFilter;
                            $mediaQuery->whereHas('media', function ($subQuery) use ($mediaType) {
                                $subQuery->where('file_type', $mediaType);
                            })->orWhere('file_type', $mediaType);
                        }
                    });
            });
        }

        // Apply hashtag filter
        if ($hashtagFilter) {
            $query->whereHas('hashtags', function ($hashtagQuery) use ($hashtagFilter) {
                $hashtagQuery->where('slug', $hashtagFilter)
                    ->orWhere('name', $hashtagFilter);
            });
        }

        // Get posts
        $posts = $query->latest()
            ->limit(24) // Show more posts
            ->get();

        // Get available categories from database
        $availableCategories = $this->getAvailableCategories();

        // Get popular hashtags
        $popularHashtags = $this->getPopularHashtags();

        return view('welcome', compact('posts', 'categoryFilter', 'hashtagFilter', 'availableCategories', 'popularHashtags'));
    }

    /**
     * Get available categories from posts that have media (FIXED VERSION)
     */
    private function getAvailableCategories()
    {
        // Get primary categories
        $primaryCategories = Category::whereHas('posts', function ($query) {
            $query->where(function ($q) {
                $q->whereHas('media')->orWhereNotNull('file_url');
            });
        })
            ->withCount(['posts' => function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('media')->orWhereNotNull('file_url');
                });
            }])
            ->having('posts_count', '>', 0)
            ->get();

        // Convert to regular collection to avoid merge conflicts
        $categories = collect();

        // Add database categories
        foreach ($primaryCategories as $category) {
            $categories->push((object) [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
                'icon' => $category->icon,
                'posts_count' => $category->posts_count,
                'type' => 'category'
            ]);
        }

        // Add media type categories
        $mediaTypes = Post::whereNotNull('file_type')
            ->orWhereHas('media')
            ->get()
            ->flatMap(function ($post) {
                $types = [];
                if ($post->file_type) {
                    $types[] = $post->file_type;
                }
                foreach ($post->media as $media) {
                    $types[] = $media->file_type;
                }
                return $types;
            })
            ->unique();

        foreach ($mediaTypes as $type) {
            $slug = $type === 'image' ? 'foto' : $type;
            $name = $type === 'image' ? 'Foto' : ucfirst($type);

            // Count posts for this media type
            $count = Post::where(function ($q) use ($type) {
                $q->where('file_type', $type)
                    ->orWhereHas('media', function ($mediaQuery) use ($type) {
                        $mediaQuery->where('file_type', $type);
                    });
            })->count();

            if ($count > 0) {
                $categories->push((object) [
                    'id' => 'media_' . $type,
                    'slug' => $slug,
                    'name' => $name,
                    'icon' => $this->getMediaTypeIcon($type),
                    'posts_count' => $count,
                    'type' => 'media'
                ]);
            }
        }

        // Remove duplicates by slug and sort by name
        return $categories->unique('slug')->sortBy('name')->values();
    }

    /**
     * Get popular hashtags from posts with media
     */
    private function getPopularHashtags($limit = 10)
    {
        return Hashtag::whereHas('posts', function ($query) {
            $query->where(function ($q) {
                $q->whereHas('media')->orWhereNotNull('file_url');
            });
        })
            ->withCount(['posts' => function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('media')->orWhereNotNull('file_url');
                });
            }])
            ->orderBy('posts_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get icon for media types
     */
    private function getMediaTypeIcon($type)
    {
        $icons = [
            'image' => '📸',
            'video' => '🎬',
            'audio' => '🎵',
        ];

        return $icons[$type] ?? '📁';
    }

    /**
     * Determine the primary category display for a post
     */
    private function getPostCategoryDisplay($post)
    {
        // Priority: primary category > media type
        if ($post->category) {
            return [
                'slug' => $post->category->slug,
                'name' => $post->category->name,
                'icon' => $post->category->icon
            ];
        }

        // Fallback to media type
        $mediaType = null;
        if ($post->media->first()) {
            $mediaType = $post->media->first()->file_type;
        } elseif ($post->file_type) {
            $mediaType = $post->file_type;
        }

        if ($mediaType) {
            return [
                'slug' => $mediaType === 'image' ? 'foto' : $mediaType,
                'name' => $mediaType === 'image' ? 'Foto' : ucfirst($mediaType),
                'icon' => $this->getMediaTypeIcon($mediaType)
            ];
        }

        return [
            'slug' => 'all',
            'name' => 'General',
            'icon' => '📁'
        ];
    }

    /**
     * API endpoint to filter posts dynamically
     */
    public function filterPosts(Request $request)
    {
        $category = $request->get('category', 'all');
        $hashtag = $request->get('hashtag', null);

        $query = Post::with(['media', 'user', 'category', 'hashtags'])
            ->withCount(['likes', 'comments'])
            ->where(function ($q) {
                $q->whereHas('media')->orWhereNotNull('file_url');
            });

        // Apply filters (same logic as index method)
        if ($category !== 'all') {
            $query->where(function ($q) use ($category) {
                $q->whereHas('category', function ($categoryQuery) use ($category) {
                    $categoryQuery->where('slug', $category)
                        ->orWhere('name', 'like', '%' . str_replace('-', ' ', $category) . '%');
                })
                    ->orWhere(function ($mediaQuery) use ($category) {
                        if (in_array($category, ['video', 'audio', 'image', 'foto'])) {
                            $mediaType = $category === 'foto' ? 'image' : $category;
                            $mediaQuery->whereHas('media', function ($subQuery) use ($mediaType) {
                                $subQuery->where('file_type', $mediaType);
                            })->orWhere('file_type', $mediaType);
                        }
                    });
            });
        }

        if ($hashtag) {
            $query->whereHas('hashtags', function ($hashtagQuery) use ($hashtag) {
                $hashtagQuery->where('slug', $hashtag)->orWhere('name', $hashtag);
            });
        }

        $posts = $query->latest()->limit(24)->get();

        // Format response for AJAX
        $formattedPosts = $posts->map(function ($post) {
            $firstMedia = $post->media->first();
            $previewUrl = null;
            $mediaType = null;

            if ($firstMedia) {
                $previewUrl = $firstMedia->file_url;
                $mediaType = $firstMedia->file_type;
            } elseif ($post->file_url) {
                $previewUrl = $post->file_url;
                $mediaType = $post->file_type;
            }

            $categoryDisplay = $this->getPostCategoryDisplay($post);

            return [
                'id' => $post->id,
                'title' => $post->title,
                'preview_url' => $previewUrl,
                'media_type' => $mediaType,
                'category' => $categoryDisplay,
                'user' => [
                    'name' => $post->user->name
                ],
                'stats' => [
                    'likes_count' => $post->likes_count,
                    'comments_count' => $post->comments_count
                ],
                'url' => route('posts.show', $post)
            ];
        });

        return response()->json([
            'posts' => $formattedPosts,
            'total' => $formattedPosts->count()
        ]);
    }
}
