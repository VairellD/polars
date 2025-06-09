<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
// TAMBAH INI - Route untuk AJAX filtering di welcome page
Route::get('/filter-posts', [WelcomeController::class, 'filterPosts'])->name('filter.posts');


Route::get('/gotoabout', function () {
    return view('about');
});

// Authentication Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public profile routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
// Route to serve profile pictures
Route::get('/profile-pictures/{path}', [App\Http\Controllers\ProfileController::class, 'showPictures'])
    ->where('path', '.*')
    ->name('profile.picture');

// Protected profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Post routes (accessible to all)
Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');

// Hashtag and Category routes (public)
Route::get('/hashtag/{hashtag}', [PostController::class, 'byHashtag'])->name('posts.hashtag');
Route::get('/category/{category}', [PostController::class, 'byCategory'])->name('posts.category');

// Protected post routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [\App\Http\Controllers\PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [\App\Http\Controllers\PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');

    // Like and comment routes
    Route::post('/posts/{post}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/posts/{post}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');

// PERBAIKI - Admin Routes dengan middleware admin dan route yang lengkap
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Profile/Statistik
    Route::get('/profile', [ProfileController::class, 'adminProfile'])->name('profile');

    // Admin User Management
    Route::get('/users', [ProfileController::class, 'adminDeleteUsers'])->name('delete-users');
    Route::delete('/users/{user}', [ProfileController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/bulk-delete', [ProfileController::class, 'destroyMultipleUsers'])->name('users.bulk-delete');

    // TAMBAH INI - Admin Post Management
    Route::get('/posts', [ProfileController::class, 'adminDeletePosts'])->name('delete-posts');
    Route::delete('/posts/{post}', [ProfileController::class, 'destroyPost'])->name('posts.destroy');
    Route::post('/posts/bulk-delete', [ProfileController::class, 'destroyMultiplePosts'])->name('posts.bulk-delete');

    // Admin Feed
    Route::get('/feed', [ProfileController::class, 'adminFeed'])->name('feed');
});

require __DIR__ . '/auth.php';
