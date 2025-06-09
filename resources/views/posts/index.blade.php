@extends('layouts.app')

@section('title', 'Feed | Polars')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Left Sidebar - Profile & Navigation -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px;">
                            @auth
                                <img src="{{ Auth::user()->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="Profile">
                            @else
                                <img src="{{ asset('assets/default-avatar.png') }}" class="img-fluid" alt="Guest">
                            @endauth
                        </div>
                        <div>
                            @auth
                                <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                                <p class="text-muted mb-0 small">{{ '@' . Auth::user()->username }}</p>
                            @else
                                <h6 class="mb-0 fw-bold">Guest</h6>
                                <p class="text-muted mb-0 small">Not signed in</p>
                            @endauth
                        </div>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}" class="nav-link active px-3 py-2 rounded-pill mb-1">
                                <i class="bi bi-house-door-fill me-2"></i> Home
                            </a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a href="{{ route('profile.show', Auth::user()) }}" class="nav-link px-3 py-2 rounded-pill mb-1">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('posts.create') }}" class="nav-link px-3 py-2 rounded-pill mb-1">
                                <i class="bi bi-plus-circle me-2"></i> Create Post
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link px-3 py-2 rounded-pill mb-1">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link px-3 py-2 rounded-pill mb-1">
                                <i class="bi bi-person-plus me-2"></i> Register
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Feed -->
        <div class="col-lg-6 col-md-8">
            <!-- Create Post Card -->
            @auth
                <div class="card border-0 shadow-sm rounded-lg mb-3">
                    <div class="card-body p-3">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="quick-post-form">
                            @csrf
                            <div class="d-flex">
                                <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                                    <img src="{{ Auth::user()->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="Profile">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="hidden" name="title" value="Post from {{ Auth::user()->name }}">

                                    <!-- Editable content area instead of textarea -->
                                    <div class="form-control border-0 mb-2 post-input"
                                         contenteditable="true"
                                         data-placeholder="What's happening?"
                                         id="post-content"></div>
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
                                            <button type="button" class="btn btn-sm text-primary border-0 upload-trigger" data-type="image" title="Add Images">
                                                <i class="bi bi-image"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm text-primary border-0 upload-trigger" data-type="video" title="Add Videos">
                                                <i class="bi bi-camera-video"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm text-primary border-0 upload-trigger" data-type="audio" title="Add Audio">
                                                <i class="bi bi-music-note-beamed"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm text-primary border-0" title="Add Emoji">
                                                <i class="bi bi-emoji-smile"></i>
                                            </button>

                                            <!-- Category Selector -->
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm text-primary border-0 dropdown-toggle" id="categoryDropdown" data-bs-toggle="dropdown" title="Add Category">
                                                    <i class="bi bi-tags"></i>
                                                </button>
                                                <ul class="dropdown-menu category-dropdown" aria-labelledby="categoryDropdown">
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Tugas Akhir" data-icon="🎓">🎓 Tugas Akhir</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Audio" data-icon="🎵">🎵 Audio</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Video" data-icon="🎬">🎬 Video</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Foto" data-icon="📸">📸 Foto</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Animasi" data-icon="🎭">🎭 Animasi</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="UI/UX" data-icon="🎨">🎨 UI/UX</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Nirmana" data-icon="🖼️">🖼️ Nirmana</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="Gambar Berulak" data-icon="🔄">🔄 Gambar Berulak</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="VR" data-icon="🥽">🥽 VR</a></li>
                                                    <li><a class="dropdown-item category-option" href="#" data-category="AR" data-icon="📱">📱 AR</a></li>
                                                </ul>
                                            </div>

                                            <!-- Hidden file inputs that support multiple files -->
                                            <input type="file" name="files[]" class="d-none" id="file-upload-image" accept="image/*" multiple>
                                            <input type="file" name="files[]" class="d-none" id="file-upload-video" accept="video/*" multiple>
                                            <input type="file" name="files[]" class="d-none" id="file-upload-audio" accept="audio/*" multiple>

                                            <!-- Track selected files by type -->
                                            <input type="hidden" name="has_images" id="has-images" value="0">
                                            <input type="hidden" name="has_videos" id="has-videos" value="0">
                                            <input type="hidden" name="has_audio" id="has-audio" value="0">
                                        </div>
                                        <button type="submit" id="submit-post-btn" class="btn btn-primary rounded-pill px-4" disabled>Post</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-lg mb-3">
                    <div class="card-body text-center py-4">
                        <p class="mb-3">Sign in to share your posts and join the conversation.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill">Register</a>
                    </div>
                </div>
            @endauth

            <!-- Feed Items -->
            @forelse($posts as $post)
                <div class="card border-0 shadow-sm rounded-lg mb-3 post-card" id="post-{{ $post->id }}">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <img src="{{ $post->user->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="{{ $post->user->name }}">
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-1">{{ $post->user->name }}</span>
                                        <span class="text-muted small">{{ '@' . $post->user->username }}</span>
                                        <span class="text-muted small ms-2">· {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('posts.show', $post) }}">View post</a></li>
                                            @if(Auth::check() && Auth::id() === $post->user_id)
                                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a></li>
                                                <li>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <p class="my-2">{{ $post->description }}</p>

                                 <!-- Display Hashtags -->
                                 @if($post->hashtags && $post->hashtags->count() > 0)
                                 <div class="mb-2">
                                     @foreach($post->hashtags as $hashtag)
                                         {{-- <a href="{{ route('posts.hashtag', $hashtag->slug) }}" class="hashtag-display me-1 text-decoration-none">#{{ $hashtag->name }}</a> --}}
                                         <span class="hashtag-display me-1 text-decoration-none">#{{ $hashtag->name }}</span>
                                     @endforeach
                                 </div>
                             @endif

                             <!-- Display Category -->
                             @if($post->category)
                                 <div class="mb-2">
                                     <a href="{{ route('posts.category', $post->category->slug) }}" class="category-display me-1 text-decoration-none">
                                         {{ $post->category->icon ?? '📁' }} {{ $post->category->name }}
                                     </a>
                                 </div>
                             @endif

                                {{-- Display media files (Twitter/X style layout) --}}
@if($post->media->count() > 0)
    <div class="media-container">
        {{-- Layout berdasarkan jumlah media --}}
        <div class="media-grid-{{ min($post->media->count(), 4) }}">
            @foreach($post->media->take(4) as $index => $media)
                <div class="media-item">
                    @if($media->file_type == 'image')
                        <img src="{{ $media->file_url }}" alt="Post image">
                    @elseif($media->file_type == 'video')
                        <video autoplay muted loop playsinline>
                            <source src="{{ $media->file_url }}" type="video/{{ $media->file_extension }}">
                            Your browser does not support the video tag.
                        </video>
                        <div class="media-play-button">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    @elseif($media->file_type == 'audio')
    <div class="soundcloud-player">
        <div class="soundcloud-header d-flex align-items-center mb-2">
            <div class="audio-artwork">
                <i class="bi bi-music-note-beamed"></i>
            </div>
            <div class="audio-info ms-2">
                <div class="audio-title">{{ pathinfo($media->file_url, PATHINFO_FILENAME) }}</div>
                <small class="audio-artist">{{ $post->user->name }}</small>
            </div>
            <div class="audio-play-btn ms-auto">
                <i class="bi bi-play-fill"></i>
            </div>
        </div>
        <div class="waveform-container">
            <div class="waveform-bg"></div>
            <div class="waveform-progress"></div>
            <div class="waveform-time">0:00</div>
        </div>
        <audio class="d-none audio-element" data-media-id="{{ $media->id }}">
            <source src="{{ $media->file_url }}" type="audio/{{ $media->file_extension }}">
        </audio>
    </div>
    @endif

                    {{-- Tambahkan overlay +N untuk gambar terakhir jika ada lebih dari 4 media --}}
                    @if($index == 3 && $post->media->count() > 4)
                        <div class="media-more-overlay">+{{ $post->media->count() - 4 }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Indikator gallery jika ada lebih dari 1 media --}}
        @if($post->media->count() > 1)
            <div class="media-gallery-indicator">
                <i class="bi bi-collection"></i>
            </div>
        @endif
    </div>
@elseif($post->file_url)
    {{-- Legacy display for old posts with single file --}}
    <div class="media-container">
        <div class="media-grid-1">
            @if($post->file_type == 'image')
                <img src="{{ $post->file_url }}" alt="Post image">
            @elseif($post->file_type == 'video')
                <video autoplay muted loop playsinline>
                    <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                    Your browser does not support the video tag.
                </video>
                <div class="media-play-button">
                    <i class="bi bi-play-fill"></i>
                </div>
            @elseif($post->file_type == 'audio')
                <div class="soundcloud-player">
        <div class="soundcloud-header d-flex align-items-center mb-2">
            <div class="audio-artwork">
                <i class="bi bi-music-note-beamed"></i>
            </div>
            <div class="audio-info ms-2">
                <div class="audio-title">{{ pathinfo($media->file_url, PATHINFO_FILENAME) }}</div>
                <small class="audio-artist">{{ $post->user->name }}</small>
            </div>
            <div class="audio-play-btn ms-auto">
                <i class="bi bi-play-fill"></i>
            </div>
        </div>
        <div class="waveform-container">
            <div class="waveform-bg"></div>
            <div class="waveform-progress"></div>
            <div class="waveform-time">0:00</div>
        </div>
        <audio class="d-none audio-element" data-media-id="{{ $media->id }}">
            <source src="{{ $media->file_url }}" type="audio/{{ $media->file_extension }}">
        </audio>
    </div>
            @endif
        </div>
    </div>
@endif
                                <!-- Action Buttons -->
                                <div class="d-flex mt-2">
                                    <button class="btn btn-sm btn-link text-muted me-3 like-button"
                                            data-post-id="{{ $post->id }}"
                                            data-liked="{{ $post->isLikedBy(Auth::user() ?? null) ? 'true' : 'false' }}">
                                        <i class="bi {{ $post->isLikedBy(Auth::user() ?? null) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                        <span class="likes-count ms-1">{{ $post->likes_count }}</span>
                                    </button>
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-link text-muted me-3">
                                        <i class="bi bi-chat"></i>
                                        <span class="ms-1">{{ $post->comments_count }}</span>
                                    </a>
                                    <button class="btn btn-sm btn-link text-muted share-button">
                                        <i class="bi bi-share"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-light rounded-lg">
                    <div class="mb-3">
                        <i class="bi bi-chat-square-text" style="font-size: 3rem;"></i>
                    </div>
                    <h5>No posts yet</h5>
                    <p class="text-muted">Be the first to share something!</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Right Sidebar - Trending & Suggestions -->
        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <!-- Trending Section -->
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Trending Tags</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="text-decoration-none">#multimedia</a>
                        <a href="#" class="text-decoration-none">#design</a>
                        <a href="#" class="text-decoration-none">#photography</a>
                        <a href="#" class="text-decoration-none">#animation</a>
                        <a href="#" class="text-decoration-none">#videography</a>
                    </div>
                </div>
            </div>

            <!-- Who to Follow -->
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">Who to Follow</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 d-flex align-items-center p-3">
                        <div class="rounded-circle overflow-hidden me-3" style="width: 40px; height: 40px;">
                            <img src="{{ asset('assets/default-avatar.png') }}" class="img-fluid" alt="User">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fs-6">User Name</h6>
                            <small class="text-muted">@username</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary rounded-pill">Follow</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Content editable placeholder handling
        const postContent = document.getElementById('post-content');
        const postDescriptionInput = document.getElementById('post-description-input');
        const submitPostBtn = document.getElementById('submit-post-btn');

        // Hashtag and Category arrays
        // Arrays for hashtags and categories (both support multiple)
    let hashtags = [];
    let selectedCategories = []; // Changed to array for multiple categories


        // Multiple file preview elements
        const filePreviewContainer = document.getElementById('multiple-files-preview-container');
        const previewWrapper = document.getElementById('files-preview-wrapper');
        const clearAllBtn = document.getElementById('clear-all-files');

        // File type counters
        let fileCounters = {
            image: 0,
            video: 0,
            audio: 0
        };

        // Media badge elements
        const mediaBadges = {
            container: document.getElementById('media-type-badges'),
            image: {
                badge: document.getElementById('badge-image'),
                count: document.getElementById('image-count')
            },
            video: {
                badge: document.getElementById('badge-video'),
                count: document.getElementById('video-count')
            },
            audio: {
                badge: document.getElementById('badge-audio'),
                count: document.getElementById('audio-count')
            }
        };

         // ====== HASHTAG FUNCTIONALITY ======
    function extractHashtagsFromText(text) {
        const hashtagRegex = /#[a-zA-Z0-9_\u0080-\uFFFF]+/g;
        const matches = text.match(hashtagRegex) || [];
        return matches.map(tag => tag.slice(1)); // Remove # symbol
    }

    function removeHashtagsFromText(text) {
        const hashtagRegex = /#[a-zA-Z0-9_\u0080-\uFFFF]+/g;
        return text.replace(hashtagRegex, '').replace(/\s+/g, ' ').trim();
    }

    function processHashtagsOnSpace() {
        const currentText = postContent.innerText;
        console.log('Processing text for hashtags:', currentText);

        const extractedHashtags = extractHashtagsFromText(currentText);

        if (extractedHashtags.length > 0) {
            // Add new hashtags (avoid duplicates)
            extractedHashtags.forEach(tag => {
                if (!hashtags.includes(tag)) {
                    hashtags.push(tag);
                }
            });

            // Remove hashtags from text
            const cleanText = removeHashtagsFromText(currentText);
            postContent.innerHTML = cleanText;

            // Set cursor at end
            const range = document.createRange();
            const sel = window.getSelection();
            if (postContent.childNodes.length > 0) {
                range.setStartAfter(postContent.childNodes[postContent.childNodes.length - 1]);
            } else {
                range.setStart(postContent, 0);
            }
            range.collapse(true);
            sel.removeAllRanges();
            sel.addRange(range);

            updateHashtagDisplay();
            updateSubmitButton();

            console.log('Updated hashtags:', hashtags);
            console.log('Clean text:', cleanText);
        }
    }

    function updateHashtagDisplay() {
        const hashtagsContainer = document.getElementById('hashtags-container');
        const hashtagsList = document.getElementById('hashtags-list');

        if (hashtags.length > 0) {
            hashtagsList.innerHTML = '';
            hashtags.forEach((hashtag, index) => {
                const hashtagElement = document.createElement('span');
                hashtagElement.className = 'hashtag-tag';
                hashtagElement.innerHTML = `
                    #${hashtag}
                    <button type="button" class="hashtag-remove" data-index="${index}">×</button>
                `;
                hashtagsList.appendChild(hashtagElement);
            });
            hashtagsContainer.style.display = 'block';
        } else {
            hashtagsContainer.style.display = 'none';
        }
    }

    // ====== CATEGORY FUNCTIONALITY (Multiple Support) ======
    document.querySelectorAll('.category-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryName = this.dataset.category;
            const categoryIcon = this.dataset.icon;

            const category = {
                name: categoryName,
                icon: categoryIcon
            };

            // Check if category already selected
            const existingIndex = selectedCategories.findIndex(cat => cat.name === categoryName);

            if (existingIndex === -1) {
                // Add new category
                selectedCategories.push(category);
            } else {
                // Remove existing category (toggle)
                selectedCategories.splice(existingIndex, 1);
            }

            updateCategoryDisplay();
            updateSubmitButton();

            console.log('Updated categories:', selectedCategories);
        });
    });

    function updateCategoryDisplay() {
        const categoriesContainer = document.getElementById('categories-container');
        const categoriesList = document.getElementById('categories-list');

        if (selectedCategories.length > 0) {
            categoriesList.innerHTML = '';
            selectedCategories.forEach((category, index) => {
                const categoryElement = document.createElement('span');
                categoryElement.className = 'category-tag';
                categoryElement.innerHTML = `
                    ${category.icon} ${category.name}
                    <button type="button" class="category-remove" data-index="${index}">×</button>
                `;
                categoriesList.appendChild(categoryElement);
            });
            categoriesContainer.style.display = 'block';
        } else {
            categoriesContainer.style.display = 'none';
        }
    }

    // ====== REMOVE HANDLERS ======
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('hashtag-remove')) {
            const index = parseInt(e.target.dataset.index);
            hashtags.splice(index, 1);
            updateHashtagDisplay();
            updateSubmitButton();
            console.log('Removed hashtag, remaining:', hashtags);
        }

        if (e.target.classList.contains('category-remove')) {
            const index = parseInt(e.target.dataset.index);
            selectedCategories.splice(index, 1);
            updateCategoryDisplay();
            updateSubmitButton();
            console.log('Removed category, remaining:', selectedCategories);
        }
    });

    // ====== INPUT EVENT HANDLERS ======
    if (postContent) {
        postContent.addEventListener('input', function() {
            const text = this.innerText.trim();
            const cleanText = removeHashtagsFromText(text);
            postDescriptionInput.value = cleanText;
            updateSubmitButton();
        });

        postContent.addEventListener('keydown', function(e) {
            if (e.key === ' ' || e.key === 'Enter') {
                setTimeout(() => processHashtagsOnSpace(), 10);
            }
        });

        postContent.addEventListener('paste', function(e) {
            setTimeout(() => processHashtagsOnSpace(), 50);
        });

        postContent.addEventListener('focus', function() {
            if (this.textContent.trim() === '') {
                this.classList.add('focused');
            }
        });

        postContent.addEventListener('blur', function() {
    if (this.textContent.trim() === '') {
        this.classList.remove('focused');
    }
    // Process any remaining hashtags when user leaves the input
    setTimeout(() => {
        processHashtagsOnSpace();
        updateSubmitButton(); // Add this line!
    }, 10);
});



    }

        // Helper functions
        function hasUploadedFiles() {
            return fileCounters.image > 0 || fileCounters.video > 0 || fileCounters.audio > 0;
        }

        function updateSubmitButton() {
    // Fix 1: Get clean content (without hashtags)
    let hasContent = false;
    if (postContent && postContent.innerText.trim()) {
        const cleanText = removeHashtagsFromText(postContent.innerText.trim());
        hasContent = cleanText.length > 0;
    }

    const hasFiles = hasUploadedFiles();
    const hasHashtags = hashtags.length > 0;

    // Fix 2: Use selectedCategories instead of categories
    const hasCategories = selectedCategories.length > 0;

    // Enable button if any condition is met
    const shouldEnable = hasContent || hasFiles || hasHashtags || hasCategories;
    submitPostBtn.disabled = !shouldEnable;

    // Debug logging
    console.log('Submit button update:', {
        hasContent,
        hasFiles,
        hasHashtags,
        hasCategories,
        shouldEnable,
        disabled: submitPostBtn.disabled
    });
}

        function updateHiddenInputs() {
            document.getElementById('has-images').value = fileCounters.image > 0 ? 1 : 0;
            document.getElementById('has-videos').value = fileCounters.video > 0 ? 1 : 0;
            document.getElementById('has-audio').value = fileCounters.audio > 0 ? 1 : 0;
        }

        function updateMediaBadges() {
            // Update badge counts
            mediaBadges.image.count.textContent = fileCounters.image;
            mediaBadges.video.count.textContent = fileCounters.video;
            mediaBadges.audio.count.textContent = fileCounters.audio;

            // Show/hide individual badges
            mediaBadges.image.badge.style.display = fileCounters.image > 0 ? 'inline-flex' : 'none';
            mediaBadges.video.badge.style.display = fileCounters.video > 0 ? 'inline-flex' : 'none';
            mediaBadges.audio.badge.style.display = fileCounters.audio > 0 ? 'inline-flex' : 'none';

            // Show/hide container
            mediaBadges.container.style.display = hasUploadedFiles() ? 'block' : 'none';
        }
          // Submit form handler
         // ====== FORM SUBMISSION ======
    if (document.getElementById('quick-post-form')) {
        document.getElementById('quick-post-form').addEventListener('submit', function(e) {
            console.log('Form submission started');

            // Set clean description
            if (postContent) {
                const cleanText = removeHashtagsFromText(postContent.innerText.trim());
                postDescriptionInput.value = cleanText;
            }

            // Create hidden inputs for hashtags and categories with proper format
            const form = this;

            // Remove existing hidden inputs if any
            const existingHashtagInputs = form.querySelectorAll('input[name="hashtags[]"]');
            const existingCategoryInputs = form.querySelectorAll('input[name="categories[]"]');
            existingHashtagInputs.forEach(input => input.remove());
            existingCategoryInputs.forEach(input => input.remove());

            // Add hashtag inputs (one for each hashtag)
            hashtags.forEach((hashtag, index) => {
                const hashtagInput = document.createElement('input');
                hashtagInput.type = 'hidden';
                hashtagInput.name = 'hashtags[]';  // Array format for Laravel
                hashtagInput.value = hashtag;
                form.appendChild(hashtagInput);
                console.log(`Added hashtag input [${index}]:`, hashtag);
            });

            // Add category inputs (one for each category)
            selectedCategories.forEach((category, index) => {
                const categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = 'categories[]';  // Array format for Laravel
                categoryInput.value = category.name;
                form.appendChild(categoryInput);
                console.log(`Added category input [${index}]:`, category.name);
            });

            console.log('Form data summary:', {
                description: postDescriptionInput.value,
                hashtags: hashtags,
                categories: selectedCategories.map(c => c.name),
                hasFiles: hasUploadedFiles()
            });

            // Validation
            if (!postDescriptionInput.value &&
                !hasUploadedFiles() &&
                hashtags.length === 0 &&
                selectedCategories.length === 0) {
                e.preventDefault();
                alert('Please add some content, media, hashtags, or categories to your post');
                return false;
            }

            console.log('Form validation passed, submitting...');
        });
    }

        // File upload triggers
        document.querySelectorAll('.upload-trigger').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                document.getElementById(`file-upload-${type}`).click();
            });
        });

        // Handle file selection for each type
        const fileUploads = {
            image: document.getElementById('file-upload-image'),
            video: document.getElementById('file-upload-video'),
            audio: document.getElementById('file-upload-audio')
        };

        Object.keys(fileUploads).forEach(type => {
            const fileInput = fileUploads[type];

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        // Process each selected file
                        for (let i = 0; i < this.files.length; i++) {
                            const file = this.files[i];

                            // Create preview element
                            const previewItem = document.createElement('div');
                            previewItem.className = 'preview-item position-relative m-1';
                            previewItem.dataset.type = type;
                            previewItem.dataset.name = file.name;

                            // Create remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-0 rounded-circle';
                            removeBtn.style.width = '20px';
                            removeBtn.style.height = '20px';
                            removeBtn.style.zIndex = '10';
                            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                            removeBtn.addEventListener('click', function() {
                                previewItem.remove();
                                fileCounters[type]--;
                                updateMediaBadges();
                                updateHiddenInputs();

                                // Hide container if no files
                                if (!hasUploadedFiles()) {
                                    filePreviewContainer.style.display = 'none';
                                }

                                updateSubmitButton();
                            });

                            // Create preview content based on file type
                            if (type === 'image') {
                                const img = document.createElement('img');
                                img.src = URL.createObjectURL(file);
                                img.className = 'img-fluid rounded';
                                img.style.width = '100px';
                                img.style.height = '100px';
                                img.style.objectFit = 'cover';
                                previewItem.appendChild(img);
                            } else if (type === 'video') {
                                const video = document.createElement('video');
                                video.src = URL.createObjectURL(file);
                                video.className = 'rounded';
                                video.style.width = '100px';
                                video.style.height = '100px';
                                video.style.objectFit = 'cover';
                                video.muted = true;
                                video.controls = false;
                                // Auto-play on hover
                                video.addEventListener('mouseover', function() {
                                    this.play();
                                });
                                video.addEventListener('mouseout', function() {
                                    this.pause();
                                    this.currentTime = 0;
                                });
                                previewItem.appendChild(video);
                            } else if (type === 'audio') {
                                // For audio, show icon and filename
                                const audioContainer = document.createElement('div');
                                audioContainer.className = 'bg-light rounded d-flex flex-column align-items-center justify-content-center';
                                audioContainer.style.width = '100px';
                                audioContainer.style.height = '100px';

                                const icon = document.createElement('i');
                                icon.className = 'bi bi-file-earmark-music text-success';
                                icon.style.fontSize = '2rem';

                                const name = document.createElement('div');
                                name.className = 'small text-truncate text-center px-1';
                                name.style.width = '100%';
                                name.textContent = file.name;

                                audioContainer.appendChild(icon);
                                audioContainer.appendChild(name);
                                previewItem.appendChild(audioContainer);
                            }

                            // Add remove button to preview item
                            previewItem.appendChild(removeBtn);

                            // Append preview item to container
                            previewWrapper.appendChild(previewItem);

                            // Update counter
                            fileCounters[type]++;
                        }

                        // Show preview container
                        filePreviewContainer.style.display = 'block';

                        // Update media badges
                        updateMediaBadges();
                        updateHiddenInputs();

                        // Update submit button
                        updateSubmitButton();
                    }
                });
            }
        });

        // Clear all files button
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                // Clear all previews
                previewWrapper.innerHTML = '';

                // Reset file inputs
                Object.values(fileUploads).forEach(input => {
                    if (input) input.value = '';
                });

                // Reset counters
                fileCounters = { image: 0, video: 0, audio: 0 };

                // Update UI
                filePreviewContainer.style.display = 'none';
                updateMediaBadges();
                updateHiddenInputs();
                // updateCategoryDisplay();
                updateSubmitButton();
            });
        }

        // Like button functionality with animation
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const icon = this.querySelector('i');
                const count = this.querySelector('.likes-count');

                // Animate heart immediately
                if (this.dataset.liked === 'true') {
                    // Unlike
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart', 'animate-unlike');
                    setTimeout(() => {
                        icon.classList.remove('animate-unlike');
                    }, 300);
                    this.dataset.liked = 'false';
                    count.textContent = parseInt(count.textContent) - 1;
                } else {
                    // Like
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger', 'animate-like');
                    setTimeout(() => {
                        icon.classList.remove('animate-like');
                    }, 300);
                    this.dataset.liked = 'true';
                    count.textContent = parseInt(count.textContent) + 1;
                }

                // Send to server
                @if(Auth::check())
                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update UI with server response (in case there's a sync issue)
                        if (data.liked !== (this.dataset.liked === 'true')) {
                            // Revert animation if server response differs from client action
                            this.dataset.liked = data.liked ? 'true' : 'false';

                            if (data.liked) {
                                icon.classList.remove('bi-heart');
                                icon.classList.add('bi-heart-fill', 'text-danger');
                            } else {
                                icon.classList.remove('bi-heart-fill', 'text-danger');
                                icon.classList.add('bi-heart');
                            }

                            count.textContent = data.likes_count;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                @else
                    // Revert animation and redirect to login
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 300);
                @endif
            });
        });

        // Share button functionality
        document.querySelectorAll('.share-button').forEach(button => {
            button.addEventListener('click', function() {
                const postElement = this.closest('.post-card');
                const postId = postElement.id.replace('post-', '');
                const postUrl = `${window.location.origin}/posts/${postId}`;

                if (navigator.share) {
                    navigator.share({
                        title: 'Check out this post',
                        url: postUrl
                    })
                    .catch(error => console.error('Error sharing:', error));
                } else {
                    // Fallback
                    navigator.clipboard.writeText(postUrl).then(() => {
                        // Create a temporary tooltip
                        const tooltip = document.createElement('div');
                        tooltip.className = 'position-absolute bg-dark text-white px-2 py-1 rounded';
                        tooltip.style.fontSize = '12px';
                        tooltip.style.bottom = '100%';
                        tooltip.style.left = '50%';
                        tooltip.style.transform = 'translateX(-50%)';
                        tooltip.style.zIndex = '1000';
                        tooltip.textContent = 'Link copied!';

                        this.style.position = 'relative';
                        this.appendChild(tooltip);

                        setTimeout(() => {
                            tooltip.remove();
                        }, 2000);
                    });
                }
            });
        });

        // Media handling
document.querySelectorAll('[data-play-on-hover]').forEach(video => {
    video.addEventListener('mouseover', function() {
        this.play();
    });

    video.addEventListener('mouseout', function() {
        this.pause();
        this.currentTime = 0;
    });
});

// Audio player functionality (SoundCloud style)
document.querySelectorAll('.audio-player-wrapper').forEach(wrapper => {
    const audio = wrapper.querySelector('audio');
    const playButton = wrapper.querySelector('.play-button');

    if (playButton && audio) {
        playButton.addEventListener('click', function() {
            if (audio.paused) {
                // Pause all other audio players first
                document.querySelectorAll('.audio-element').forEach(otherAudio => {
                    if (otherAudio !== audio) {
                        otherAudio.pause();
                        const otherWrapper = otherAudio.closest('.audio-player-wrapper');
                        if (otherWrapper) {
                            otherWrapper.querySelector('.play-button').classList.remove('playing');
                        }
                    }
                });

                audio.play();
                this.classList.add('playing');
            } else {
                audio.pause();
                this.classList.remove('playing');
            }
        });

        // Update button state when audio ends
        audio.addEventListener('ended', function() {
            playButton.classList.remove('playing');
        });
    }
});

// Audio items in grid
// Enhanced SoundCloud style audio player
document.querySelectorAll('.soundcloud-player').forEach(player => {
    const audio = player.querySelector('audio');
    const playBtn = player.querySelector('.audio-play-btn');
    const waveformProgress = player.querySelector('.waveform-progress');
    const waveformContainer = player.querySelector('.waveform-container');
    const timeDisplay = player.querySelector('.waveform-time');

    if (audio && playBtn) {
        // Play/Pause functionality
        playBtn.addEventListener('click', function(e) {
            e.stopPropagation();

            if (audio.paused) {
                // Pause all other audio first
                document.querySelectorAll('audio').forEach(otherAudio => {
                    if (otherAudio !== audio && !otherAudio.paused) {
                        otherAudio.pause();
                        const otherPlayer = otherAudio.closest('.soundcloud-player');
                        if (otherPlayer) {
                            otherPlayer.querySelector('.audio-play-btn').classList.remove('playing');
                            otherPlayer.querySelector('.waveform-container').classList.remove('waveform-animated');
                        }
                    }
                });

                // Play this audio
                audio.play();
                playBtn.classList.add('playing');
                waveformContainer.classList.add('waveform-animated');
            } else {
                // Pause this audio
                audio.pause();
                playBtn.classList.remove('playing');
                waveformContainer.classList.remove('waveform-animated');
            }
        });

        // Click on waveform to seek
        waveformContainer.addEventListener('click', function(e) {
            e.stopPropagation();

            if (!audio.paused || audio.readyState >= 2) {
                const rect = waveformContainer.getBoundingClientRect();
                const clickPosition = (e.clientX - rect.left) / rect.width;
                audio.currentTime = audio.duration * clickPosition;

                // If click but not playing, start playing
                if (audio.paused) {
                    playBtn.click();
                }
            }
        });

        // Update progress bar and time
        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                const progress = (audio.currentTime / audio.duration) * 100;
                waveformProgress.style.width = `${progress}%`;

                // Update time display
                const minutes = Math.floor(audio.currentTime / 60);
                const seconds = Math.floor(audio.currentTime % 60).toString().padStart(2, '0');
                timeDisplay.textContent = `${minutes}:${seconds}`;
            }
        });

        // Reset when ended
        audio.addEventListener('ended', function() {
            playBtn.classList.remove('playing');
            waveformContainer.classList.remove('waveform-animated');
            waveformProgress.style.width = '0%';
            timeDisplay.textContent = '0:00';
        });

        // Load metadata to setup
        audio.addEventListener('loadedmetadata', function() {
            const minutes = Math.floor(audio.duration / 60);
            const seconds = Math.floor(audio.duration % 60).toString().padStart(2, '0');
            timeDisplay.textContent = `0:00 / ${minutes}:${seconds}`;
        });
    }
});

// Media items click to view in detail
document.querySelectorAll('.image-item, .video-item').forEach(item => {
    item.addEventListener('click', function() {
        const postId = this.closest('.post-card').id.replace('post-', '');
        window.location.href = `/posts/${postId}`;
    });
});

// Media container click functionality
document.querySelectorAll('.media-container').forEach(container => {
    container.addEventListener('click', function(e) {
        // Prevent default action when clicking play button or audio control
        if (e.target.closest('.play-button') ||
            e.target.closest('.audio-thumbnail') ||
            e.target.closest('.waveform-container') ||
            e.target.closest('.media-play-button')) {
            return;
        }

        // Navigate to post detail
        const postId = this.closest('.post-card').id.replace('post-', '');
        window.location.href = `/posts/${postId}`;
    });
});

// Enhanced video interaction
document.querySelectorAll('.media-item video').forEach(video => {
    let clickCount = 0;
    let clickTimer = null;

    video.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent bubbling to container

        clickCount++;

        if (clickCount === 1) {
            clickTimer = setTimeout(() => {
                // Single click - toggle mute/unmute instead of play/pause
                if (this.muted) {
                    // Unmute video
                    this.muted = false;

                    // Show playing with sound indicator
                    const playButton = this.parentElement.querySelector('.media-play-button');
                    if (playButton) {
                        playButton.innerHTML = '<i class="bi bi-volume-up"></i>';
                        playButton.style.opacity = '1';

                        // Hide the indicator after 2 seconds
                        setTimeout(() => {
                            playButton.style.opacity = '0';
                        }, 2000);
                    }
                } else {
                    // Mute video but keep playing
                    this.muted = true;

                    // Show muted indicator
                    const playButton = this.parentElement.querySelector('.media-play-button');
                    if (playButton) {
                        playButton.innerHTML = '<i class="bi bi-volume-mute"></i>';
                        playButton.style.opacity = '1';

                        // Hide the indicator after 2 seconds
                        setTimeout(() => {
                            playButton.style.opacity = '0';
                        }, 2000);
                    }
                }

                clickCount = 0;
            }, 300); // 300ms delay for double click detection
        } else if (clickCount === 2) {
            // Double click - navigate to post
            clearTimeout(clickTimer);
            clickCount = 0;

            const postId = this.closest('.post-card').id.replace('post-', '');
            window.location.href = `/posts/${postId}`;
        }
    });
});

// Make the play button work for videos
document.querySelectorAll('.media-play-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent bubbling to container

        const video = this.closest('.media-item').querySelector('video');
        if (video) {
            if (video.muted) {
                video.muted = false; // Unmute when clicked
                this.innerHTML = '<i class="bi bi-volume-up"></i>';
            } else {
                video.muted = true;
                this.innerHTML = '<i class="bi bi-volume-mute"></i>';
            }

            // Show the button temporarily
            this.style.opacity = '1';
            setTimeout(() => {
                if (!video.paused) {
                    this.style.opacity = '0';
                }
            }, 2000);
        }
    });
});
    });
</script>

@endsection

@section('styles')
<style>
    body {
        background-color: #f7f9fa;
    }

    .card {
        border-radius: 12px !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
    }

    .post-card {
        transition: all 0.2s ease;
    }

    .post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .btn-link {
        text-decoration: none;
        color: #6c757d;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
    }

    .btn-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
        color: #495057;
    }

    .nav-link {
        color: #212529;
    }

    .nav-link:hover, .nav-link.active {
        background-color: rgba(29, 161, 242, 0.1);
        color: #1da1f2;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #1da1f2;
    }

    .rounded-pill {
        border-radius: 50px !important;
    }

    /* Post input styling */
    .post-input {
        min-height: 60px;
        overflow-y: auto;
    }

    .post-input:empty:not(.focused):before {
        content: attr(data-placeholder);
        color: #aaa;
        pointer-events: none;
    }

    .post-input:focus {
        outline: none;
        box-shadow: none;
    }

    /* Hashtag styling */
    .hashtag-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        margin: 2px;
        gap: 4px;
    }

    .hashtag-remove {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
    }

    .hashtag-remove:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .hashtag-display {
        color: #1da1f2;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
    }

    .hashtag-display:hover {
        text-decoration: underline;
    }

    /* Category styling */
    .category-tag {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        margin: 2px;
        gap: 4px;
    }

    .category-remove {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
    }

    .category-remove:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .category-display {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
    }

    /* Category dropdown styling */
    .category-dropdown {
        max-height: 300px;
        overflow-y: auto;
    }

    .category-dropdown .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
    }

    .category-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Like button animations */
    @keyframes like-animation {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }

    @keyframes unlike-animation {
        0% { transform: scale(1); }
        50% { transform: scale(0.8); }
        100% { transform: scale(1); }
    }

    .animate-like {
        animation: like-animation 0.3s ease;
    }

    .animate-unlike {
        animation: unlike-animation 0.3s ease;
    }

    /* Preview item styling */
.preview-item {
    position: relative;
    margin: 8px;
    transition: all 0.2s ease;
    overflow: hidden;
    border-radius: 8px;
}

.preview-item:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.preview-item img,
.preview-item video {
    object-fit: cover;
    width: 100px;
    height: 100px;
    border-radius: 8px;
}

.preview-item button {
    opacity: 0.7;
}

.preview-item:hover button {
    opacity: 1;
}


/* Media Grid Styling Enhanced - Twitter/X Style */
.media-container {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    margin: 12px 0;
}

/* Different layouts based on media count */
.media-grid-1 img,
.media-grid-1 video {
    width: 100%;
    border-radius: 16px;
    max-height: 500px;
    object-fit: contain;
    background-color: #000;
}

/* 2-image layout */
.media-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 2px;
    height: 280px;
}

.media-grid-2 .media-item {
    height: 100%;
    overflow: hidden;
}

.media-grid-2 .media-item:first-child {
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
}

.media-grid-2 .media-item:last-child {
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
}

/* 3-image layout */
.media-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    grid-gap: 2px;
    height: 340px;
}

.media-grid-3 .media-item:first-child {
    grid-row: span 2;
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
}

.media-grid-3 .media-item:nth-child(2) {
    border-top-right-radius: 16px;
}

.media-grid-3 .media-item:last-child {
    border-bottom-right-radius: 16px;
}

/* 4-image layout */
.media-grid-4 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    grid-gap: 2px;
    height: 340px;
}

.media-grid-4 .media-item:first-child {
    border-top-left-radius: 16px;
}

.media-grid-4 .media-item:nth-child(2) {
    border-top-right-radius: 16px;
}

.media-grid-4 .media-item:nth-child(3) {
    border-bottom-left-radius: 16px;
}

.media-grid-4 .media-item:last-child {
    border-bottom-right-radius: 16px;
}

/* Common media item styles */
.media-item {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.media-item img,
.media-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* More indicator for 4+ images */
.media-more-overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
}

/* Media play button styling */
.media-play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 24px;
    opacity: 0;
    transition: opacity 0.2s;
}

.media-item:hover .media-play-button {
    opacity: 1;
}

/* Audio SoundCloud style */
/* SoundCloud Style Audio Player */
.soundcloud-player {
    background: linear-gradient(135deg, #ff7700, #ff3300);
    border-radius: 8px;
    padding: 15px;
    color: white;
    position: relative;
    overflow: hidden;
}

.audio-artwork {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.audio-artwork i {
    font-size: 1.2rem;
    color: white;
}

.audio-info {
    overflow: hidden;
}

.audio-title {
    font-weight: bold;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.audio-artist {
    font-size: 12px;
    opacity: 0.8;
    display: block;
}

.audio-play-btn {
    width: 36px;
    height: 36px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.audio-play-btn:hover {
    transform: scale(1.1);
}

.audio-play-btn i {
    color: #ff5500;
    font-size: 1.2rem;
    margin-left: 2px;
}

.audio-play-btn.playing i {
    margin-left: 0;
}

.audio-play-btn.playing i::before {
    content: "\F4F1"; /* Bootstrap icon code for pause */
}

.waveform-container {
    position: relative;
    height: 60px;
    margin-top: 5px;
}

.waveform-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-repeat: repeat-x;
    background-position: 0 center;
    background-size: auto 70%;
    opacity: 0.5;
    background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,25 C2.5,25 2.5,15 5,15 C7.5,15 7.5,25 10,25 C12.5,25 12.5,20 15,20 C17.5,20 17.5,25 20,25 C22.5,25 22.5,15 25,15 C27.5,15 27.5,25 30,25 C32.5,25 32.5,5 35,5 C37.5,5 37.5,25 40,25 C42.5,25 42.5,10 45,10 C47.5,10 47.5,25 50,25 C52.5,25 52.5,20 55,20 C57.5,20 57.5,25 60,25 C62.5,25 62.5,15 65,15 C67.5,15 67.5,25 70,25 C72.5,25 72.5,10 75,10 C77.5,10 77.5,25 80,25 C82.5,25 82.5,15 85,15 C87.5,15 87.5,25 90,25 C92.5,25 92.5,5 95,5 C97.5,5 97.5,25 100,25' stroke='white' stroke-width='1' fill='none' /%3E%3C/svg%3E");
}

.waveform-progress {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 0%;
    overflow: hidden;
    transition: width 0.1s linear;
}

.waveform-progress::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-repeat: repeat-x;
    background-position: 0 center;
    background-size: auto 70%;
    background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,25 C2.5,25 2.5,15 5,15 C7.5,15 7.5,25 10,25 C12.5,25 12.5,20 15,20 C17.5,20 17.5,25 20,25 C22.5,25 22.5,15 25,15 C27.5,15 27.5,25 30,25 C32.5,25 32.5,5 35,5 C37.5,5 37.5,25 40,25 C42.5,25 42.5,10 45,10 C47.5,10 47.5,25 50,25 C52.5,25 52.5,20 55,20 C57.5,20 57.5,25 60,25 C62.5,25 62.5,15 65,15 C67.5,15 67.5,25 70,25 C72.5,25 72.5,10 75,10 C77.5,10 77.5,25 80,25 C82.5,25 82.5,15 85,15 C87.5,15 87.5,25 90,25 C92.5,25 92.5,5 95,5 C97.5,5 97.5,25 100,25' stroke='white' stroke-width='1' fill='none' /%3E%3C/svg%3E");
}

.waveform-time {
    position: absolute;
    bottom: 0;
    right: 5px;
    background: rgba(0,0,0,0.3);
    color: white;
    font-size: 10px;
    padding: 2px 4px;
    border-radius: 4px;
    font-family: monospace;
}

/* Compact Audio Player for Grid Layout */
.media-grid-2 .soundcloud-player,
.media-grid-3 .soundcloud-player:not(:first-child),
.media-grid-4 .soundcloud-player {
    padding: 10px;
}

.media-grid-2 .audio-artwork,
.media-grid-3 .soundcloud-player:not(:first-child) .audio-artwork,
.media-grid-4 .soundcloud-player .audio-artwork {
    width: 30px;
    height: 30px;
}

.media-grid-2 .audio-play-btn,
.media-grid-3 .soundcloud-player:not(:first-child) .audio-play-btn,
.media-grid-4 .soundcloud-player .audio-play-btn {
    width: 30px;
    height: 30px;
}

.media-grid-2 .waveform-container,
.media-grid-3 .soundcloud-player:not(:first-child) .waveform-container,
.media-grid-4 .soundcloud-player .waveform-container {
    height: 40px;
}

/* Audio Player Animation */
@keyframes sound-wave {
    0% { opacity: 0.6; }
    50% { opacity: 1; }
    100% { opacity: 0.6; }
}

.waveform-animated .waveform-bg,
.waveform-animated .waveform-progress::before {
    animation: sound-wave 1.2s infinite;
}

/* Media count badge */
.media-count {
    position: absolute;
    right: 10px;
    top: 10px;
    background: rgba(0,0,0,0.5);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
}

/* Multi-view indicator (1/4, etc) */
.media-indicator {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
}

/* Media gallery indicator */
.media-gallery-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    font-size: 14px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

    /* Media upload button hover effects */
    .upload-trigger:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
</style>
@endsection
