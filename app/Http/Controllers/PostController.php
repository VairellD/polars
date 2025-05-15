<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
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
        $posts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     * UPDATED: Now supports better file type detection and validation
     */
    /**
 * Store a newly created post in storage with multiple media files.
 */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:102400', // 100MB max for each file
            'has_images' => 'nullable|boolean',
            'has_videos' => 'nullable|boolean',
            'has_audio' => 'nullable|boolean',
        ]);

        // Create the post
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Process files if uploaded
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            
            foreach ($files as $file) {
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

                            // Log success
                            Log::info("File uploaded successfully: {$path}");
                        } catch (\Exception $e) {
                            Log::error('File upload failed: ' . $e->getMessage());
                            // Continue to next file instead of failing the entire upload
                        }
                    }
                }
            }
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Your post has been created successfully!');
    }

    /**
     * Display the specified post.
     * FIXED: Now handles guest users properly
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user'])->loadCount(['likes', 'comments']);
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

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
     public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:102400', // 100MB max for each file
        ]);

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Handle file uploads pada update jika perlu
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            
            foreach ($files as $file) {
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
                        } catch (\Exception $e) {
                            Log::error('File upload failed: ' . $e->getMessage());
                        }
                    }
                }
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
}