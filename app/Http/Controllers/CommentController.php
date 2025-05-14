<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this import

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(), // Change from auth()->id()
            'content' => $request->content,
        ]);

        // Increment the comments_count on the post
        $post->increment('comments_count');

        if ($request->ajax()) {
            return response()->json([
                'comment' => $comment->load('user'),
                'comments_count' => $post->fresh()->comments_count,
            ]);
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Replace $this->authorize with Gate facade or check manually
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id) {
            abort(403);
        }

        $post = $comment->post;
        $comment->delete();

        // Decrement the comments_count on the post
        $post->decrement('comments_count');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'comments_count' => $post->fresh()->comments_count,
            ]);
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment deleted successfully!');
    }
}
