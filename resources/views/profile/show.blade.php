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
                    <a href="{{ route('posts.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle me-2"></i> Create Post
                    </a>
                    @if(Auth::check() && Auth::user()->is_admin)
                        <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-gear me-2"></i> Admin Profile
                        </a>
                    @else
                        <a href="{{ route('profile.show', Auth::user()) }}"
                        class="list-group-item list-group-item-action {{ Auth::id() === $user->id ? 'active' : '' }}">
                            <i class="bi bi-person me-2"></i> Profile
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
                                <div class="rounded-circle overflow-hidden mx-auto mb-3"
                                    style="width: 150px; height: 150px;">
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
                                        <a href="{{ $user->url }}" target="_blank"
                                            rel="noopener noreferrer">{{ $user->url }}</a>
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
                                                <i
                                                    class="bi bi-heart{{ $post->isLikedBy(Auth::user()) ? '-fill text-danger' : '' }}"></i>
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
                                        {{-- <a href="{{ route('posts.create') }}" class="btn btn-primary">Create your first
                                            post</a> --}}
                                        <a href="#" class="btn btn-primary" x-data @click.prevent="$dispatch('open-quick-post')">
                                            Create your first post
                                        </a>
                                        {{-- <a href="#" id="show-post-form" class="btn btn-primary">Create your first post</a> --}}

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
    {{-- Modal Create --}}
    <div x-data="{ show: false }" x-show="show" x-transition.opacity @open-quick-post.window="show = true"
        @close-modal.window="show = false" @keydown.escape.window="show = false" id="quick-post-modal-container"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        <div @click.away="show = false" class="card border-0 shadow-sm rounded-lg w-full max-w-lg">
            <div class="card-body p-3">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="quick-post-form">
                    @csrf
                    <div class="d-flex">
                        <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                            <img src="{{ Auth::user()->profile_picture ?? asset('assets/default-avatar.png') }}"
                                class="img-fluid" alt="Profile">
                        </div>
                        <div class="flex-grow-1">
                            <input type="hidden" name="title" value="Post from {{ Auth::user()->name }}">

                            <!-- Editable content area instead of textarea -->
                            <div class="form-control border-0 w-full mb-2 post-input" contenteditable="true"
                                data-placeholder="What's happening?" id="post-content"></div>
                            <input type="hidden" name="description" id="post-description-input">

                            <!-- Hashtags Display -->
                            <div id="hashtags-container" class="mb-2" style="display: none;">
                                <div class="d-flex flex-wrap gap-1" id="hashtags-list">
                                    <!-- Hashtags will be displayed here -->
                                </div>
                            </div>
                            <input type="hidden" name="hashtags" id="hashtags-input">

                            <!-- Categories Display -->
                            <div id="categories-container" class="mb-2" style="display: none;">
                                <div class="d-flex flex-wrap gap-1" id="categories-list">
                                    <!-- Categories will be displayed here -->
                                </div>
                            </div>
                            <input type="hidden" name="categories" id="categories-input">

                            <!-- Media Preview Container for Multiple Files -->
                            <div id="multiple-files-preview-container" class="mb-2" style="display: none;">
                                <div class="rounded border p-2 bg-light">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="text-muted small">Media Preview</div>
                                        <button type="button" class="btn btn-sm text-primary" id="clear-all-files">
                                            Clear All
                                        </button>
                                    </div>
                                    <div id="files-preview-wrapper" class="d-flex flex-wrap gap-2">
                                        <!-- Media previews will be added here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <!-- Media Type Badges -->
                            <div id="media-type-badges" class="mb-2" style="display: none;">
                                <div class="d-flex flex-wrap gap-1">
                                    <span id="badge-image" class="badge bg-primary rounded-pill" style="display: none;">
                                        <i class="bi bi-image"></i> <span id="image-count">0</span> Images
                                    </span>
                                    <span id="badge-video" class="badge bg-danger rounded-pill" style="display: none;">
                                        <i class="bi bi-camera-video"></i> <span id="video-count">0</span> Videos
                                    </span>
                                    <span id="badge-audio" class="badge bg-success rounded-pill" style="display: none;">
                                        <i class="bi bi-music-note-beamed"></i> <span id="audio-count">0</span> Audio
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <!-- Separate icons for different media types -->
                                    <button type="button" class="btn btn-sm text-primary border-0 upload-trigger"
                                        data-type="image" title="Add Images">
                                        <i class="bi bi-image"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm text-primary border-0 upload-trigger"
                                        data-type="video" title="Add Videos">
                                        <i class="bi bi-camera-video"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm text-primary border-0 upload-trigger"
                                        data-type="audio" title="Add Audio">
                                        <i class="bi bi-music-note-beamed"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm text-primary border-0" title="Add Emoji">
                                        <i class="bi bi-emoji-smile"></i>
                                    </button>

                                    <!-- Category Selector -->
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm text-primary border-0 dropdown-toggle"
                                            id="categoryDropdown" data-bs-toggle="dropdown" title="Add Category">
                                            <i class="bi bi-tags"></i>
                                        </button>
                                        <ul class="dropdown-menu category-dropdown" aria-labelledby="categoryDropdown">
                                            <li><a class="dropdown-item category-option" href="#"
                                                    data-category="Tugas Akhir" data-icon="🎓">🎓 Tugas Akhir</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="Audio"
                                                    data-icon="🎵">🎵 Audio</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="Video"
                                                    data-icon="🎬">🎬 Video</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="Foto"
                                                    data-icon="📸">📸 Foto</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="Animasi"
                                                    data-icon="🎭">🎭 Animasi</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="UI/UX"
                                                    data-icon="🎨">🎨 UI/UX</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="Nirmana"
                                                    data-icon="🖼️">🖼️ Nirmana</a></li>
                                            <li><a class="dropdown-item category-option" href="#"
                                                    data-category="Gambar Berulak" data-icon="🔄">🔄 Gambar Berulak</a>
                                            </li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="VR"
                                                    data-icon="🥽">🥽 VR</a></li>
                                            <li><a class="dropdown-item category-option" href="#" data-category="AR"
                                                    data-icon="📱">📱 AR</a></li>
                                        </ul>
                                    </div>

                                    <!-- Hidden file inputs that support multiple files -->
                                    <input type="file" name="files[]" class="d-none" id="file-upload-image" accept="image/*"
                                        multiple>
                                    <input type="file" name="files[]" class="d-none" id="file-upload-video" accept="video/*"
                                        multiple>
                                    <input type="file" name="files[]" class="d-none" id="file-upload-audio" accept="audio/*"
                                        multiple>

                                    <!-- Track selected files by type -->
                                    <input type="hidden" name="has_images" id="has-images" value="0">
                                    <input type="hidden" name="has_videos" id="has-videos" value="0">
                                    <input type="hidden" name="has_audio" id="has-audio" value="0">
                                </div>
                                <button type="submit" id="submit-post-btn" class="btn btn-primary rounded-pill px-4"
                                    disabled>Post</button>
                            </div>
                        </div>
                    </div>
                </form>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trigger = document.getElementById('show-post-form');
            const modal = document.getElementById('quick-post-modal');

            if (trigger && modal) {
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    modal.style.display = 'block';
                    modal.scrollIntoView({ behavior: 'smooth' });
                });
            }
        });
    </script>
@endsection