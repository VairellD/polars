@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('posts.index') }}" class="text-decoration-none text-dark">
                    <i class="bi bi-arrow-left"></i> Back to Feed
                </a>
            </div>

            <!-- Post Card -->
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body p-3">
                    <div class="d-flex">
                        <div class="rounded-circle overflow-hidden me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                            <img src="{{ $post->user->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="{{ $post->user->name }}">
                        </div>

                        <div class="flex-grow-1">
                            <!-- Post Header -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $post->user->name }}</h5>
                                    <p class="text-muted mb-1 small">{{ '@' . $post->user->username }}</p>
                                </div>

                                <!-- Post Options -->
                                @if(Auth::check() && Auth::id() === $post->user_id)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a></li>
                                            <li>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- Post Content -->
                            <p class="my-3 fs-5">{{ $post->description }}</p>
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
                            <!-- Post Media -->
                           <!-- Post Media -->
@if($post->media->count() > 0)
    <div class="media-gallery mb-4">
        <!-- Main Media Display -->
        <div class="main-media-container mb-2">
            @php $firstMedia = $post->media->first(); @endphp
            <div class="main-media-wrapper">
                @if($firstMedia->file_type == 'image')
                    <img src="{{ $firstMedia->file_url }}" class="img-fluid w-100 rounded" alt="Post image">
                @elseif($firstMedia->file_type == 'video')
                    <video controls autoplay class="w-100 rounded">
                        <source src="{{ $firstMedia->file_url }}" type="video/{{ $firstMedia->file_extension }}">
                        Your browser does not support the video tag.
                    </video>
       @elseif($firstMedia->file_type == 'audio')
<div class="main-media-wrapper">
    <div class="w-100 d-flex justify-content-center align-items-center" style="max-height: 500px;">
        <div class="soundcloud-player w-100" style="max-width: 100%;">
            <div class="soundcloud-header d-flex align-items-center mb-2">
                <div class="audio-artwork">
                    <i class="bi bi-music-note-beamed"></i>
                </div>
                <div class="audio-info ms-2">
                    <div class="audio-title">{{ pathinfo($firstMedia->file_url, PATHINFO_FILENAME) }}</div>
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
            <audio class="d-none audio-element" data-media-id="{{ $firstMedia->id }}">
                <source src="{{ $firstMedia->file_url }}" type="audio/{{ $firstMedia->file_extension }}">
            </audio>
        </div>
    </div>
</div>
                @endif
            </div>
        </div>

        <!-- Thumbnails for other media -->
        @if($post->media->count() > 1)
            <div class="media-thumbnails d-flex gap-2 overflow-auto py-2">
                @foreach($post->media as $index => $media)
                    <div class="media-thumbnail {{ $index === 0 ? 'active' : '' }}" data-media-index="{{ $index }}">
                        @if($media->file_type == 'image')
                            <img src="{{ $media->file_url }}" class="img-thumbnail" alt="Thumbnail">
                        @elseif($media->file_type == 'video')
                            <div class="video-thumbnail">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                        @elseif($media->file_type == 'audio')
                            <div class="audio-thumbnail-small">
                                <i class="bi bi-music-note-beamed"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@elseif($post->file_url)
    <!-- Legacy Media Display -->
    <div class="rounded overflow-hidden mb-4">
        @if($post->file_type == 'image')
            <img src="{{ $post->file_url }}" class="img-fluid w-100 rounded" alt="Post image">
        @elseif($post->file_type == 'video')
            <video controls autoplay class="w-100 rounded">
                <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                Your browser does not support the video tag.
            </video>

        @elseif($firstMedia->file_type == 'audio')
<div class="main-media-wrapper">
    <div class="w-100 d-flex justify-content-center align-items-center" style="max-height: 500px;">
        <div class="soundcloud-player w-100" style="max-width: 100%;">
            <div class="soundcloud-header d-flex align-items-center mb-2">
                <div class="audio-artwork">
                    <i class="bi bi-music-note-beamed"></i>
                </div>
                <div class="audio-info ms-2">
                    <div class="audio-title">{{ pathinfo($firstMedia->file_url, PATHINFO_FILENAME) }}</div>
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
            <audio class="d-none audio-element" data-media-id="{{ $firstMedia->id }}">
                <source src="{{ $firstMedia->file_url }}" type="audio/{{ $firstMedia->file_extension }}">
            </audio>
        </div>
    </div>
</div>

        @endif
    </div>
@endif

                            <!-- Post Metadata -->
                            <div class="text-muted mb-3">
                                <small>{{ $post->created_at->format('g:i A · M j, Y') }}</small>
                            </div>

                            <!-- Post Stats -->
                            <div class="d-flex border-top border-bottom py-3 mb-3">
                                <div class="me-4">
                                    <span class="fw-bold post-likes-count">{{ $post->likes_count }}</span>
                                    <span class="text-muted">{{ Str::plural('Like', $post->likes_count) }}</span>
                                </div>
                                <div>
                                    <span class="fw-bold">{{ $post->comments_count }}</span>
                                    <span class="text-muted">{{ Str::plural('Comment', $post->comments_count) }}</span>
                                </div>
                            </div>

                            <!-- Post Actions -->
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-lg btn-link text-muted like-button flex-grow-1"
                                        data-post-id="{{ $post->id }}"
                                        data-liked="{{ $userLiked ? 'true' : 'false' }}">
                                    <i class="bi {{ $userLiked ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                    <span class="d-none d-md-inline ms-2">Like</span>
                                </button>
                                <button class="btn btn-lg btn-link text-muted flex-grow-1" onclick="document.getElementById('comment-input').focus()">
                                    <i class="bi bi-chat"></i>
                                    <span class="d-none d-md-inline ms-2">Comment</span>
                                </button>
                                <button class="btn btn-lg btn-link text-muted flex-grow-1" onclick="sharePost()">
                                    <i class="bi bi-share"></i>
                                    <span class="d-none d-md-inline ms-2">Share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comment Form -->
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-body p-3">
                    @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST" id="comment-form">
                            @csrf
                            <div class="d-flex">
                                <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <img src="{{ Auth::user()->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="Profile">
                                </div>
                                <div class="flex-grow-1">
                                    <!-- Contenteditable div instead of textarea -->
                                    <div class="form-control border-0 comment-input"
                                         contenteditable="true"
                                         data-placeholder="Write a comment..."
                                         id="comment-input"></div>
                                    <input type="hidden" name="content" id="comment-content">

                                    <div class="text-end mt-2">
                                        <button type="submit" id="submit-comment-btn" class="btn btn-primary rounded-pill px-4" disabled>Comment</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <p class="mb-2">Sign in to join the conversation</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill">Login</a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Comments List -->
            <h5 class="mb-3">Comments ({{ $post->comments_count }})</h5>

            @forelse($post->comments as $comment)
                <div class="card border-0 shadow-sm rounded-lg mb-3" id="comment-{{ $comment->id }}">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <img src="{{ $comment->user->profile_picture ?? asset('assets/default-avatar.png') }}" class="img-fluid" alt="{{ $comment->user->name }}">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>

                                    @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $post->user_id))
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-2 mb-0">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 bg-light rounded">
                    <p class="mb-0 text-muted">No comments yet. Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Comment input handling
        const commentInput = document.getElementById('comment-input');
        const commentContent = document.getElementById('comment-content');
        const submitCommentBtn = document.getElementById('submit-comment-btn');

        if (commentInput) {
            commentInput.addEventListener('input', function() {
                // Update hidden input value
                commentContent.value = this.innerText.trim();

                // Enable/disable comment button based on content
                submitCommentBtn.disabled = !this.innerText.trim();
            });

            commentInput.addEventListener('focus', function() {
                if (this.textContent.trim() === '') {
                    this.classList.add('focused');
                }
            });

            commentInput.addEventListener('blur', function() {
                if (this.textContent.trim() === '') {
                    this.classList.remove('focused');
                }
            });

            // Submit form handler
            document.getElementById('comment-form').addEventListener('submit', function(e) {
                commentContent.value = commentInput.innerText.trim();

                if (!commentContent.value) {
                    e.preventDefault();
                    alert('Please add some content to your comment');
                }
            });

        }

        // Like button functionality with animation
        const likeButton = document.querySelector('.like-button');
        if (likeButton) {
            likeButton.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const icon = this.querySelector('i');
                const likesCountElement = document.querySelector('.post-likes-count');

                // Animate heart immediately
                if (this.dataset.liked === 'true') {
                    // Unlike
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart', 'animate-unlike');
                    setTimeout(() => {
                        icon.classList.remove('animate-unlike');
                    }, 300);
                    this.dataset.liked = 'false';
                    likesCountElement.textContent = parseInt(likesCountElement.textContent) - 1;
                } else {
                    // Like
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger', 'animate-like');
                    setTimeout(() => {
                        icon.classList.remove('animate-like');
                    }, 300);
                    this.dataset.liked = 'true';
                    likesCountElement.textContent = parseInt(likesCountElement.textContent) + 1;
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

                            likesCountElement.textContent = data.likes_count;
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
        }

        // Scroll to comments if url has #comments
        if (window.location.hash === '#comments') {
            document.querySelector('h5').scrollIntoView({behavior: 'smooth'});
        }

        // Media gallery thumbnails functionality
document.querySelectorAll('.media-thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        const mediaIndex = parseInt(this.dataset.mediaIndex);
        const mediaItems = @json($post->media);

        // Update active thumbnail
        document.querySelectorAll('.media-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        this.classList.add('active');

        // Update main media display
        const mainContainer = document.querySelector('.main-media-container');
        const mediaItem = mediaItems[mediaIndex];

        let mediaContent = '';

        if (mediaItem.file_type === 'image') {
            mediaContent = `<img src="${mediaItem.file_url}" class="img-fluid w-100 rounded" alt="Post image">`;
        } else if (mediaItem.file_type === 'video') {
            mediaContent = `
                <video controls autoplay class="w-100 rounded">
                    <source src="${mediaItem.file_url}" type="video/${mediaItem.file_extension}">
                    Your browser does not support the video tag.
                </video>
            `;
        } else if (mediaItem.file_type === 'audio') {
            mediaContent = `
                <div class="audio-player-wrapper soundcloud-style large-player">
                    <div class="audio-player-header d-flex align-items-center">
                        <div class="audio-thumbnail">
                            <i class="bi bi-music-note-beamed"></i>
                        </div>
                        <div class="audio-info ms-3">
                            <div class="audio-title">Audio Track</div>
                            <small class="text-muted">${mediaItem.file_extension}</small>
                        </div>
                        <div class="play-button ms-auto">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                    <div class="waveform-container">
                        <div class="waveform-placeholder"></div>
                    </div>
                    <audio controls class="d-none audio-element">
                        <source src="${mediaItem.file_url}" type="audio/${mediaItem.file_extension}">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            `;
        }

        mainContainer.innerHTML = `<div class="main-media-wrapper">${mediaContent}</div>`;

        // Reinitialize audio player if needed
        if (mediaItem.file_type === 'audio') {
            const wrapper = mainContainer.querySelector('.audio-player-wrapper');
            const audio = wrapper.querySelector('audio');
            const playButton = wrapper.querySelector('.play-button');

            playButton.addEventListener('click', function() {
                if (audio.paused) {
                    audio.play();
                    this.classList.add('playing');
                } else {
                    audio.pause();
                    this.classList.remove('playing');
                }
            });

            audio.addEventListener('ended', function() {
                playButton.classList.remove('playing');
            });
        }
    });
});

// Audio player functionality (SoundCloud style)
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
    });

    // Share functionality
    function sharePost() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $post->title }}',
                text: '{{ Str::limit($post->description, 100) }}',
                url: window.location.href
            })
            .catch(error => console.error('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support the Web Share API
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    }
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

    .form-control:focus {
        box-shadow: none;
    }

    /* Comment input styling */
    .comment-input {
        min-height: 60px;
        overflow-y: auto;
    }

    .comment-input:empty:not(.focused):before {
        content: attr(data-placeholder);
        color: #aaa;
        pointer-events: none;
    }

    .comment-input:focus {
        outline: none;
        box-shadow: none;
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

    .rounded-pill {
        border-radius: 50px !important;
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



    /* Media gallery styling */
.media-gallery {
    position: relative;
}

.main-media-container {
    border-radius: 12px;
    overflow: hidden;
    background-color: #000;
}

.main-media-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.main-media-wrapper img,
.main-media-wrapper video {
    max-height: 500px;
    width: 100%;
    object-fit: contain;
}

/* Media thumbnails */
.media-thumbnails {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding: 10px 0;
    scroll-snap-type: x mandatory;
}

.media-thumbnail {
    flex: 0 0 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    scroll-snap-align: start;
    position: relative;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.media-thumbnail.active {
    border-color: #1da1f2;
}

.media-thumbnail:hover {
    transform: scale(1.05);
}

.media-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-thumbnail,
.audio-thumbnail-small {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #222;
}

.video-thumbnail i {
    font-size: 24px;
    color: white;
}

.audio-thumbnail-small {
    background: linear-gradient(135deg, #ff7700, #ff3300);
}

.audio-thumbnail-small i {
    font-size: 24px;
    color: white;
}

/* Large audio player for details page */
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
</style>
@endsection
