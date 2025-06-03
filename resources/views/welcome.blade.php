@extends('layouts.app')

@section('title', 'WELCOME | TeRMinal')

@section('content')
    <!-- Jumbotron Section (TETAP SAMA) -->
    <div class="container-jumbo">
        <div class="jumbo-text">
            <h2 class="mb-3">Showcase Your Creativity with Polars</h2>
            <h6>Join the Teknologi Rekayasa Multimedia program and display your projects in our vibrant student gallery.</h6>
        </div>
        <div class="jumbo-button">
            @auth
                <a href="{{ route('posts.create') }}" class="btn-custom upload-button">Upload</a>
            @else
                <a href="{{ route('login') }}" class="btn-custom upload-button">Login to Upload</a>
            @endauth
            <a href="{{ route('posts.index') }}" class="btn-custom explore-button">Explore</a>
        </div>
    </div>

    <!-- Category Filter & Gallery Section -->
    <div class="container-gallery">
        <div class="container">
            <!-- Category Filter Tags -->
            <div class="category-filter">
                <button class="category-tag active" data-category="all">All</button>
                <button class="category-tag" data-category="tugas-akhir">Tugas Akhir</button>
                <button class="category-tag" data-category="audio">Audio</button>
                <button class="category-tag" data-category="video">Video</button>
                <button class="category-tag" data-category="foto">Foto</button>
                <button class="category-tag" data-category="animasi">Animasi</button>
                <button class="category-tag" data-category="ui-ux">UI/UX</button>
                <button class="category-tag" data-category="nirmana">Nirmana</button>
                <button class="category-tag" data-category="gambar-berulak">Gambar Berulak</button>
                <button class="category-tag" data-category="vr">VR</button>
                <button class="category-tag" data-category="ar">AR</button>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid">
                @if(isset($posts) && $posts->count() > 0)
                    @foreach($posts as $post)
                        @php
                            // Get the first media item for preview
                            $firstMedia = $post->media->first();
                            $previewUrl = null;
                            $mediaType = null;
                            
                            if ($firstMedia) {
                                $previewUrl = $firstMedia->file_url;
                                $mediaType = $firstMedia->file_type;
                            } elseif ($post->file_url) {
                                // Fallback to legacy file_url if no media
                                $previewUrl = $post->file_url;
                                $mediaType = $post->file_type;
                            }
                            
                            // Default placeholder if no media
                            if (!$previewUrl) {
                                $previewUrl = '/assets/placeholder.jpg';
                            }
                            
                            // Determine category based on media type or post title/description
                            $category = 'all';
                            if ($mediaType === 'video') $category = 'video';
                            elseif ($mediaType === 'audio') $category = 'audio';
                            elseif ($mediaType === 'image') $category = 'foto';
                            // You can add more logic to determine category from post content
                        @endphp
                        
                        <div class="gallery-item" data-category="{{ $category }}">
                            <a href="{{ route('posts.show', $post) }}">
                                <div class="gallery-card">
                                    @if($mediaType === 'video')
                                        <div class="media-preview video-preview">
                                            <video muted>
                                                <source src="{{ $previewUrl }}" type="video/mp4">
                                            </video>
                                            <div class="play-overlay">
                                                <i class="fas fa-play-circle"></i>
                                            </div>
                                        </div>
                                    @elseif($mediaType === 'audio')
                                        <div class="media-preview audio-preview">
                                            <i class="fas fa-music fa-3x"></i>
                                            <div class="audio-waves">
                                                <span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="media-preview image-preview">
                                            <img src="{{ $previewUrl }}" alt="{{ $post->title }}" onerror="this.src='/assets/placeholder.jpg'">
                                        </div>
                                    @endif
                                    
                                    <div class="card-info">
                                        <h5>{{ Str::limit($post->title, 40) }}</h5>
                                        <div class="card-meta">
                                            <span class="author">{{ $post->user->name }}</span>
                                            <div class="stats">
                                                <span><i class="fas fa-heart"></i> {{ $post->likes_count ?? 0 }}</span>
                                                <span><i class="fas fa-eye"></i> {{ $post->views ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-4x mb-3"></i>
                        <h4>No posts yet</h4>
                        <p>Be the first to share your creativity!</p>
                        @auth
                            <a href="{{ route('posts.create') }}" class="btn-custom mt-3">Upload Your Work</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Jumbotron Styles (TETAP SAMA) */
    .container-jumbo {
        background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 100%);
        padding: 100px 0;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .container-jumbo::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(45deg);
        pointer-events: none;
    }

    .jumbo-text h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .jumbo-text h6 {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .jumbo-button {
        margin-top: 2rem;
    }

  .btn-custom {
    padding: 12px 30px;
    min-width: 180px;
    max-width: 100%;
    text-align: center;
    font-weight: 600;
    border-radius: 25px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}



    .upload-button {
        background-color: white;
        color: #9D84B7;
        border: 2px solid white;
    }

    .upload-button:hover {
        background-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    .explore-button {
        background-color: transparent;
        color: white;
        border: 2px solid white;
    }

    .explore-button:hover {
        background-color: white;
        color: #9D84B7;
        transform: translateY(-2px);
    }

    /* Gallery Section Styles */
    .container-gallery {
        padding: 40px 0;
        background-color: #f8f9fa;
        min-height: 500px;
    }

    /* Category Filter Styles */
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
        justify-content: center;
        padding: 0 20px;
    }

    .category-tag {
        padding: 8px 20px;
        border: 2px solid #e0e0e0;
        background-color: white;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
    }

    .category-tag:hover {
        border-color: #9D84B7;
        color: #9D84B7;
    }

    .category-tag.active {
        background-color: #9D84B7;
        color: white;
        border-color: #9D84B7;
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        padding: 0 20px;
    }

    .gallery-item {
        transition: all 0.3s ease;
        opacity: 1;
    }

    .gallery-item.hidden {
        display: none;
    }

    .gallery-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .media-preview {
        width: 100%;
        height: 200px;
        position: relative;
        overflow: hidden;
        background-color: #f0f0f0;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-preview {
        position: relative;
    }

    .video-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 48px;
        opacity: 0.8;
        pointer-events: none;
    }

    .audio-preview {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 100%);
        color: white;
    }

    .audio-waves {
        display: flex;
        gap: 3px;
        margin-top: 15px;
    }

    .audio-waves span {
        width: 3px;
        height: 20px;
        background-color: white;
        animation: wave 1.2s ease-in-out infinite;
    }

    .audio-waves span:nth-child(2) { animation-delay: -1.1s; }
    .audio-waves span:nth-child(3) { animation-delay: -1.0s; }
    .audio-waves span:nth-child(4) { animation-delay: -0.9s; }
    .audio-waves span:nth-child(5) { animation-delay: -0.8s; }

    @keyframes wave {
        0%, 100% { transform: scaleY(0.5); }
        50% { transform: scaleY(1); }
    }

    .card-info {
        padding: 20px;
    }

    .card-info h5 {
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #666;
    }

    .author {
        font-weight: 500;
    }

    .stats {
        display: flex;
        gap: 15px;
    }

    .stats span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state h4 {
        color: #666;
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-jumbo {
            padding: 60px 20px;
        }

        .jumbo-text h2 {
            font-size: 1.8rem;
        }

        .jumbo-button {
        flex-direction: column;
        align-items: center;
    }

    .btn-custom {
        width: 90%;
        max-width: 300px;
        text-align: center;
    }

        .category-filter {
            padding: 0 10px;
        }

        .category-tag {
            font-size: 12px;
            padding: 6px 15px;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            padding: 0 10px;
        }

        .media-preview {
            height: 150px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryTags = document.querySelectorAll('.category-tag');
    const galleryItems = document.querySelectorAll('.gallery-item');

    categoryTags.forEach(tag => {
        tag.addEventListener('click', function() {
            // Remove active class from all tags
            categoryTags.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tag
            this.classList.add('active');

            const selectedCategory = this.getAttribute('data-category');

            // Filter gallery items
            galleryItems.forEach(item => {
                if (selectedCategory === 'all' || item.getAttribute('data-category') === selectedCategory) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });
});
</script>
@endsection