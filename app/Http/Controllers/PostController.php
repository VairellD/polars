<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this import
use Illuminate\Support\Facades\Storage;
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
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:102400', // 100MB max
            'file_type' => 'nullable|required_with:file|in:image,audio,video',
        ]);

        $postData = [
            'user_id' => Auth::id(), // Change from auth()->id()
            'title' => $request->title,
            'description' => $request->description,
        ];

        // Process file if uploaded
        if ($request->hasFile('file')) {
            // Determine allowed mime types based on file_type
            $allowed_types = [
                'image' => 'image/*',
                'audio' => 'audio/*',
                'video' => 'video/*',
            ];

            // Additional validation for file type
            $request->validate([
                'file' => 'mimes:' . $allowed_types[$request->file_type],
            ]);

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs('posts/' . $request->file_type, $filename, 'public');

            $postData['file_url'] = Storage::url($path);
            $postData['file_type'] = $request->file_type;
            $postData['file_extension'] = $extension;
            $postData['file_size'] = $file->getSize();
        }

        $post = Post::create($postData);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Your post has been created successfully!');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user'])->loadCount(['likes', 'comments']);
        $userLiked = $post->isLikedBy(Auth::user()); // Change from auth()->user()

        return view('posts.show', compact('post', 'userLiked'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Replace $this->authorize with a manual check
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
        // Replace $this->authorize with a manual check
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        // Replace $this->authorize with a manual check
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Delete the file if it exists
        if ($post->file_url) {
            // Extract the path from the URL
            $path = str_replace(Storage::url(''), '', $post->file_url);

            // Delete the file from storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
