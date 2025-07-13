@extends('layouts.app')

@section('title', 'Admin Profile | Polars')

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-3">
                <div class="list-group sticky-top" style="top: 20px">
                    <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person-gear me-2"></i> Admin Profile
                    </a>
                    <a href="{{ route('admin.delete-users') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2"></i> Delete Users
                    </a>
                    <a href="{{ route('admin.delete-posts') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-file-earmark-x me-2"></i> Delete Posts
                    </a>
                    <a href="{{ route('admin.feed') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-rss me-2"></i> Admin Feed
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Admin Profile & Statistics</h2>
                    <span class="badge bg-danger">ADMIN</span>
                </div>

                <!-- Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="rounded-circle overflow-hidden mx-auto mb-3"
                                    style="width: 120px; height: 120px;">
                                    <img src="{{ Auth::user()->profile_picture ?? 'https://cdn-icons-png.flaticon.com/512/456/456212.png' }}"
                                        class="img-fluid" alt="{{ Auth::user()->name }}">
                                </div>

                            </div>
                            <div class="col-md-9">
                                <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                                <p class="text-muted mb-2">{{ '@' . Auth::user()->username }}</p>
                                <span class="badge bg-danger mb-3">System Administrator</span>

                                @if(Auth::user()->bio)
                                    <p class="mb-0">{{ Auth::user()->bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-gear-fill display-4 mb-3"></i>
                                <h3 class="mb-1">{{ $adminChoicesCount }}</h3>
                                <p class="mb-0">Admin Choices</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-people-fill display-4 mb-3"></i>
                                <h3 class="mb-1">{{ $usersCount }}</h3>
                                <p class="mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-warning text-white border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-heart-fill display-4 mb-3"></i>
                                <h3 class="mb-1">{{ $mostLikedCount }}</h3>
                                <p class="mb-0">Most Liked Post</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">My Recent Posts</h5>
                    </div>
                    <div class="card-body">
                        @if($adminPosts->count() > 0)
                            <div class="row">
                                @foreach($adminPosts as $post)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            @if($post->file_url)
                                                <div style="height: 150px; overflow: hidden;">
                                                    @if($post->file_type == 'image')
                                                        <img src="{{ $post->file_url }}" class="card-img-top"
                                                            style="height: 100%; object-fit: cover;" alt="Post image">
                                                    @elseif($post->file_type == 'video')
                                                        <video class="card-img-top" style="height: 100%; object-fit: cover;" muted>
                                                            <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                                                        </video>
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                            <i class="bi bi-file-earmark text-muted" style="font-size: 2rem;"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h6 class="card-title">{{ Str::limit($post->title, 30) }}</h6>
                                                <p class="card-text small text-muted">{{ Str::limit($post->description, 60) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-heart"></i> {{ $post->likes_count }}
                                                        <i class="bi bi-chat ms-2"></i> {{ $post->comments_count }}
                                                    </small>
                                                    <a href="{{ route('posts.show', $post) }}"
                                                        class="btn btn-sm btn-outline-primary">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark-text display-4 text-muted mb-3"></i>
                                <p class="text-muted">No posts yet.</p>
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 12px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .list-group-item.active {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endsection