<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(User $user): View
    {
        $posts = $user->posts()->with(['user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(12);

        $postsCount = $user->posts()->count();

        return view('profile.show', compact('user', 'posts', 'postsCount'));
    }

    public function showPictures($path)
    {
        $fullPath = public_path('app/private/public/profile-pictures/' . $path);

        if (!File::exists($fullPath)) {
            return response()->file(public_path('assets/default-avatar.png'));
        }

        return response()->file($fullPath);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Manual validation to include all fields
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'url' => ['nullable', 'url', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'angkatan' => ['nullable', 'string', 'max:4'],
        ]);

        $user = $request->user();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete previous picture if exists
            if ($user->profile_picture && !str_contains($user->profile_picture, 'default-avatar.png')) {
                $oldPath = public_path(str_replace('/storage/', '', $user->profile_picture));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store directly in public directory
            $filename = 'profile-' . $user->id . '-' . time() . '.' .
                $request->file('profile_picture')->getClientOriginalExtension();
            $directory = public_path('profile-pictures');

            // Ensure directory exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Move the file
            $request->file('profile_picture')->move($directory, $filename);

            // Store URL path in database
            $user->profile_picture = '/profile-pictures/' . $filename;
        }

        // Update user fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->bio = $validated['bio'] ?? null;
        $user->url = $validated['url'] ?? null;
        $user->angkatan = $validated['angkatan'] ?? null;

        // Handle email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show', $user)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ==== ADMIN FUNCTIONS ====

    /**
     * Show admin profile/statistik page
     */
    public function adminProfile()
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $adminChoicesCount = 34; // Sesuaikan dengan logic bisnis Anda
        $usersCount = User::count();
        $mostLikedCount = Post::withCount('likes')->orderBy('likes_count', 'desc')->first()->likes_count ?? 0;

        $adminPosts = Post::where('user_id', Auth::id())
            ->with(['user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(3)
            ->get();

        return view('admin.profile', compact(
            'adminChoicesCount',
            'usersCount',
            'mostLikedCount',
            'adminPosts'
        ));
    }

    /**
     * Show admin delete user page
     */
    public function adminDeleteUsers()
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $users = User::withCount('posts')
            ->where('is_admin', false) // Don't show other admins
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.delete-users', compact('users'));
    }

    /**
     * Show admin delete posts page
     */
    public function adminDeletePosts()
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $posts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.delete-posts', compact('posts'));
    }

    /**
     * Delete specific user
     */
    public function destroyUser(User $user)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Prevent deleting admin users
        if ($user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete admin users'
            ], 403);
        }

        try {
            // Hapus semua posts user terlebih dahulu
            $user->posts()->delete();

            // Hapus user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete multiple users
     */
    public function destroyMultipleUsers(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $users = User::whereIn('id', $request->user_ids)
                ->where('is_admin', false) // Prevent deleting admins
                ->get();

            foreach ($users as $user) {
                // Hapus posts user
                $user->posts()->delete();
                // Hapus user
                $user->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($users) . ' user berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete specific post
     */
    public function destroyPost(Post $post)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Delete associated files if any
            if ($post->file_url) {
                $filePath = public_path(str_replace('/storage/', '', $post->file_url));
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete the post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete multiple posts
     */
    public function destroyMultiplePosts(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id'
        ]);

        try {
            $posts = Post::whereIn('id', $request->post_ids)->get();

            foreach ($posts as $post) {
                // Delete associated files if any
                if ($post->file_url) {
                    $filePath = public_path(str_replace('/storage/', '', $post->file_url));
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                // Delete the post
                $post->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($posts) . ' post berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus posts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show admin feed page
     */
    public function adminFeed()
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $posts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);

        return view('admin.feed', compact('posts'));
    }


}
