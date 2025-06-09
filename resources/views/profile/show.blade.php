@extends('layouts.app')

@section('title', $user->name . ' | Polars')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="list-group sticky-top" style="top: 20px">
                <a href="{{ route('posts.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-house-door me-2"></i> Home
                </a>
                <a href="{{ route('posts.create') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-plus-circle me-2"></i> Create Post
                </a>
                <a href="{{ route('profile.show', Auth::user()) }}" class="list-group-item list-group-item-action {{ Auth::id() === $user->id ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
                @if(Auth::check() && Auth::user()->is_admin)
                <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person-gear me-2"></i> Admin Profile
                </a>
                @endif
            </div>
        </div>

        <!-- Main Profile Content -->
        <div class="col-lg-9">
            <!-- Profile Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 150px; height: 150px;">
                                <img src="{{ $user->profile_picture ?? asset('assets/default-avatar.jpg') }}"
                                     class="img-fluid" alt="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h3 class="mb-0">{{ $user->name }}</h3>
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit Profile
                                    </a>
                                @endif
                            </div>
                            <h6 class="text-muted mb-3">{{ '@' . $user->username }}</h6>

                            @if($user->bio)
                                <p>{{ $user->bio }}</p>
                            @endif

                            @if($user->url)
                                <p class="mb-3">
                                    <i class="bi bi-link-45deg"></i>
                                    <a href="{{ $user->url }}" target="_blank" rel="noopener noreferrer">{{ $user->url }}</a>
                                </p>
                            @endif

                            <div class="d-flex mt-3">
                                <div class="me-4">
                                    <span class="fw-bold">{{ $postsCount }}</span>
                                    <span class="text-muted">{{ Str::plural('Post', $postsCount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Posts -->
            <h4 class="mb-3">Posts</h4>

            <div class="row">
                @forelse($posts as $post)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($post->title, 50) }}</h6>
                                <p class="card-text small text-muted">{{ Str::limit($post->description, 100) }}</p>

                                @if($post->file_url)
                                    <div class="mb-3">
                                        @if($post->file_type == 'image')
                                            <img src="{{ $post->file_url }}" class="img-fluid rounded" alt="Post image">
                                        @elseif($post->file_type == 'video')
                                            <div class="ratio ratio-16x9">
                                                <video controls class="rounded">
                                                    <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                                                </video>
                                            </div>
                                        @elseif($post->file_type == 'audio')
                                            <audio controls class="w-100">
                                                <source src="{{ $post->file_url }}" type="audio/{{ $post->file_extension }}">
                                            </audio>
                                        @endif
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">
                                            <i class="bi bi-heart{{ $post->isLikedBy(Auth::user()) ? '-fill text-danger' : '' }}"></i>
                                            {{ $post->likes_count }}
                                        </span>
                                        <span>
                                            <i class="bi bi-chat"></i>
                                            {{ $post->comments_count }}
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="stretched-link"></a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center py-5">
                            <i class="bi bi-file-earmark-text display-4 mb-3"></i>
                            <p class="mb-0">No posts yet.</p>
                            @if(Auth::id() === $user->id)
                                <p class="mt-3">
                                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create your first post</a>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 16px;
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
    }

    .card .stretched-link::after {
        border-radius: 16px;
    }
</style>
@endsection
