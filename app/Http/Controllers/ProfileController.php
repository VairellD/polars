<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
}