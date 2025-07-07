<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use App\Models\Category;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments', 'category', 'hashtags', 'media'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        $recommendedUsers = User::where('id', '!=', Auth::id())
            ->where('is_admin', false)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('posts.index', compact(['posts', 'recommendedUsers']));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created post in storage.
     * FINAL: Fully optional hashtags and categories with multiple support
     */
    public function store(Request $request)
    {


        // === VALIDATION - FIXED: Make array items optional ===
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'nullable|exists:categories,id',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'nullable|string|max:50',    // FIXED: Make nullable
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string|max:50',  // FIXED: Make nullable
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:102400',
            'has_images' => 'nullable|boolean',
            'has_videos' => 'nullable|boolean',
            'has_audio' => 'nullable|boolean',
        ]);

        // === CONTENT VALIDATION - FIXED: Include category_id ===
        $hasDescription = !empty($validated['description']);
        $hasHashtags = !empty($validated['hashtags']);
        $hasCategories = !empty($validated['categories']) || !empty($validated['category_id']);
        $hasFiles = $request->hasFile('files');

        // Require at least one type of content
        if (!$hasDescription && !$hasHashtags && !$hasCategories && !$hasFiles) {
            return back()->withErrors(['error' => 'Please add some content, hashtags, categories, or media files.']);
        }

        try {
            // === DETERMINE PRIMARY CATEGORY_ID ===
            $categoryId = $validated['category_id'] ?? null;

            // If categories array is provided, use the first one as primary
            if (!empty($validated['categories'])) {
                $firstCategoryName = trim($validated['categories'][0]);
                if (!empty($firstCategoryName)) {
                    $category = Category::firstOrCreate(
                        ['name' => $firstCategoryName],
                        [
                            'slug' => Str::slug($firstCategoryName),
                            'icon' => $this->getCategoryIcon($firstCategoryName),
                            'description' => null,
                        ]
                    );
                    $categoryId = $category->id;
                    Log::info('Primary category processed:', ['name' => $firstCategoryName, 'id' => $category->id]);
                }
            }

            // === CREATE POST ===
            $post = Post::create([
                'user_id' => Auth::id(),
                'category_id' => $categoryId,
                'title' => $validated['title'] ?? 'Post from ' . Auth::user()->name,
                'description' => $validated['description'] ?? '',
            ]);

            Log::info('Post created:', ['id' => $post->id]);

            // === HANDLE ALL CATEGORIES (INCLUDING MANY-TO-MANY) ===
            $this->handleAllCategories($post, $validated);

            // === HANDLE HASHTAGS ===
            $this->handleHashtags($post, $validated);

            // === PROCESS FILES ===
            $this->handleFileUploads($post, $request);

            // === UPDATE CATEGORY POST COUNT ===
            if ($post->category && method_exists($post->category, 'updatePostsCount')) {
                $post->category->updatePostsCount();
            }

            // === FINAL VERIFICATION ===
            $post->load(['hashtags', 'category', 'media']);
            Log::info('Post final state:', [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'hashtags_count' => $post->hashtags->count(),
                'hashtags_names' => $post->hashtags->pluck('name')->toArray(),
                'category_id' => $post->category_id,
                'category_name' => $post->category ? $post->category->name : null,
                'media_count' => $post->media->count()
            ]);

            Log::info('Post created successfully with all relations');

            return redirect()->route('posts.index')
                ->with('success', 'Your post has been created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating post:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->withErrors(['error' => 'Failed to create post: ' . $e->getMessage()]);
        }
    }

    /**
     * FIXED: Handle all categories including many-to-many relationship
     */
    private function handleAllCategories(Post $post, array $validated): void
    {
        if (empty($validated['categories'])) {
            Log::info('No custom categories to process');
            return;
        }

        Log::info('Processing all categories:', $validated['categories']);
        $categoryIds = [];

        // Include primary category if it exists
        if ($post->category_id) {
            $categoryIds[] = $post->category_id;
        }

        // Process all categories in the array
        foreach ($validated['categories'] as $index => $categoryName) {
            $categoryName = trim($categoryName);
            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    [
                        'slug' => Str::slug($categoryName),
                        'icon' => $this->getCategoryIcon($categoryName),
                        'description' => null,
                    ]
                );

                $categoryIds[] = $category->id;
                Log::info("Category [{$index}] processed:", [
                    'name' => $categoryName,
                    'id' => $category->id,
                    'was_created' => $category->wasRecentlyCreated
                ]);
            }
        }

        // Attach all categories to post (many-to-many) if relationship exists
        if (!empty($categoryIds) && method_exists($post, 'categories')) {
            $uniqueCategoryIds = array_unique($categoryIds);
            $post->categories()->attach($uniqueCategoryIds);
            Log::info('All categories attached to post:', [
                'post_id' => $post->id,
                'category_ids' => $uniqueCategoryIds,
                'count' => count($uniqueCategoryIds)
            ]);
        } else {
            Log::info('Categories relationship not available or no categories to attach');
        }
    }

    /**
     * FIXED: Handle hashtags (fully optional)
     */
    private function handleHashtags(Post $post, array $validated): void
    {
        if (empty($validated['hashtags'])) {
            Log::info('No hashtags to process');
            return;
        }

        Log::info('Processing hashtags from array...');
        $hashtagIds = [];

        foreach ($validated['hashtags'] as $index => $hashtagName) {
            $cleanHashtagName = trim($hashtagName);
            Log::info("Processing hashtag {$index}:", ['original' => $hashtagName, 'clean' => $cleanHashtagName]);

            if (!empty($cleanHashtagName)) {
                $hashtag = Hashtag::firstOrCreate(
                    ['name' => $cleanHashtagName],
                    ['slug' => Str::slug($cleanHashtagName)]
                );

                $hashtagIds[] = $hashtag->id;
                Log::info('Hashtag processed:', [
                    'name' => $cleanHashtagName,
                    'id' => $hashtag->id,
                    'was_created' => $hashtag->wasRecentlyCreated
                ]);
            }
        }

        // Attach hashtags to post
        if (!empty($hashtagIds)) {
            $post->hashtags()->attach($hashtagIds);
            Log::info('Hashtags attached to post:', [
                'post_id' => $post->id,
                'hashtag_ids' => $hashtagIds,
                'count' => count($hashtagIds)
            ]);

            // Update hashtag post counts
            foreach ($hashtagIds as $hashtagId) {
                $hashtag = Hashtag::find($hashtagId);
                if ($hashtag && method_exists($hashtag, 'updatePostsCount')) {
                    $hashtag->updatePostsCount();
                }
            }
        }
    }

    /**
     * FIXED: Handle file uploads (fully optional)
     */
    private function handleFileUploads(Post $post, Request $request): void
    {
        if (!$request->hasFile('files')) {
            Log::info('No files to upload');
            return;
        }

        $files = $request->file('files');
        $fileCount = 0;

        Log::info('Processing file uploads:', ['file_count' => count($files)]);

        foreach ($files as $index => $file) {
            if ($file->isValid()) {
                // Auto-detect file type from MIME
                $mimeType = $file->getMimeType();
                $fileType = null;

                if (str_starts_with($mimeType, 'image/')) {
                    $fileType = 'image';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $fileType = 'video';
                } elseif (str_starts_with($mimeType, 'audio/')) {
                    $fileType = 'audio';
                }

                if ($fileType) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::uuid() . '.' . $extension;

                    try {
                        $path = $file->storeAs('posts/' . $fileType, $filename, 'public');

                        // Create a media record linked to post
                        Media::create([
                            'post_id' => $post->id,
                            'file_url' => Storage::url($path),
                            'file_type' => $fileType,
                            'file_extension' => $extension,
                            'file_size' => $file->getSize(),
                        ]);

                        $fileCount++;
                        Log::info("File [{$index}] uploaded successfully: {$path}");
                    } catch (\Exception $e) {
                        Log::error("File [{$index}] upload failed: " . $e->getMessage());
                    }
                }
            }
        }
        Log::info('Files processed:', ['count' => $fileCount]);
    }

    /**
     * Get category icon based on name
     */
    private function getCategoryIcon($categoryName)
    {
        $icons = [
            'Tugas Akhir' => '🎓',
            'Audio' => '🎵',
            'Video' => '🎬',
            'Foto' => '📸',
            'Animasi' => '🎭',
            'UI/UX' => '🎨',
            'Nirmana' => '🖼️',
            'Gambar Berulang' => '🔄',
            'VR' => '🥽',
            'AR' => '📱',
        ];

        return $icons[$categoryName] ?? '📁';
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'category', 'hashtags', 'media'])
            ->loadCount(['likes', 'comments']);
        $userLiked = Auth::check() ? $post->isLikedBy(Auth::user()) : false;

        return view('posts.show', compact('post', 'userLiked'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $categories = Category::active()->ordered()->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post in storage.
     * UPDATED: Now handles hashtags and categories arrays with full optional support
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'nullable|string|max:50',    // FIXED: Make nullable
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string|max:50',  // FIXED: Make nullable
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:102400',
        ]);

        $oldCategoryId = $post->category_id;

        // Determine category_id
        $categoryId = $validated['category_id'] ?? null;

        // If categories array is provided, use the first one
        if (!empty($validated['categories'])) {
            $categoryName = trim($validated['categories'][0]);
            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    [
                        'slug' => Str::slug($categoryName),
                        'icon' => $this->getCategoryIcon($categoryName),
                        'description' => null,
                    ]
                );
                $categoryId = $category->id;
            }
        }

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $categoryId,
        ]);

        // Handle hashtags update
        if (isset($validated['hashtags'])) {
            $hashtagIds = [];

            foreach ($validated['hashtags'] as $hashtagName) {
                $cleanHashtagName = trim($hashtagName);

                if (!empty($cleanHashtagName)) {
                    $hashtag = Hashtag::firstOrCreate(
                        ['name' => $cleanHashtagName],
                        ['slug' => Str::slug($cleanHashtagName)]
                    );

                    $hashtagIds[] = $hashtag->id;
                }
            }

            // Sync hashtags (this will replace existing ones)
            $post->hashtags()->sync($hashtagIds);

            // Update hashtag post counts
            foreach ($hashtagIds as $hashtagId) {
                $hashtag = Hashtag::find($hashtagId);
                if ($hashtag && method_exists($hashtag, 'updatePostsCount')) {
                    $hashtag->updatePostsCount();
                }
            }
        } else {
            // If no hashtags provided, remove all
            $post->hashtags()->detach();
        }

        // Handle categories update (many-to-many)
        if (method_exists($post, 'categories')) {
            if (isset($validated['categories'])) {
                $categoryIds = [];

                // Include primary category
                if ($categoryId) {
                    $categoryIds[] = $categoryId;
                }

                // Process all categories
                foreach ($validated['categories'] as $categoryName) {
                    $categoryName = trim($categoryName);
                    if (!empty($categoryName)) {
                        $category = Category::firstOrCreate(
                            ['name' => $categoryName],
                            [
                                'slug' => Str::slug($categoryName),
                                'icon' => $this->getCategoryIcon($categoryName),
                                'description' => null,
                            ]
                        );
                        $categoryIds[] = $category->id;
                    }
                }

                // Sync categories
                if (!empty($categoryIds)) {
                    $post->categories()->sync(array_unique($categoryIds));
                }
            } else {
                // If no categories provided, keep only primary category
                if ($categoryId) {
                    $post->categories()->sync([$categoryId]);
                } else {
                    $post->categories()->detach();
                }
            }
        }

        // Handle file uploads if needed
        if ($request->hasFile('files')) {
            $this->handleFileUploads($post, $request);
        }

        // Update category post counts if category changed
        if ($oldCategoryId !== $post->category_id) {
            if ($oldCategoryId) {
                $oldCategory = Category::find($oldCategoryId);
                if ($oldCategory && method_exists($oldCategory, 'updatePostsCount')) {
                    $oldCategory->updatePostsCount();
                }
            }
            if ($post->category && method_exists($post->category, 'updatePostsCount')) {
                $post->category->updatePostsCount();
            }
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Delete all media files associated with the post
        foreach ($post->media as $media) {
            $path = str_replace(Storage::url(''), '', $media->file_url);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Delete legacy file if it exists (backward compatibility)
        if ($post->file_url) {
            $path = str_replace(Storage::url(''), '', $post->file_url);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    /**
     * Show posts by hashtag
     */
    public function byHashtag(Hashtag $hashtag)
    {
        $posts = $hashtag->posts()
            ->with(['user', 'likes', 'comments', 'category', 'hashtags', 'media'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.hashtag', compact('hashtag', 'posts'));
    }

    /**
     * Show posts by category
     */
    public function byCategory(Category $category)
    {
        $posts = $category->posts()
            ->with(['user', 'likes', 'comments', 'hashtags', 'media'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.category', compact('category', 'posts'));
    }
    /**
     * Handle post likes
     */
    public function like(Request $request, Post $post)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            // Unlike
            $post->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            // Like
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }
}
