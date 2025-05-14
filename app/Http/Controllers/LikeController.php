<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this import

class LikeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Toggle like for the specified post.
     */
    public function toggle(Post $post)
    {
        $user = Auth::user(); // Change from auth()->user()

        // Check if the user has already liked the post
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // User already liked this post, so unlike it
            $like->delete();

            // Decrement the likes_count on the post
            $post->decrement('likes_count');

            $message = 'Post unliked successfully!';
        } else {
            // User hasn't liked this post yet, so like it
            $post->likes()->create([
                'user_id' => $user->id,
            ]);

            // Increment the likes_count on the post
            $post->increment('likes_count');

            $message = 'Post liked successfully!';
        }

        if (request()->ajax()) {
            return response()->json([
                'likes_count' => $post->fresh()->likes_count,
                'liked' => !$like,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
