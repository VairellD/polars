@extends('layouts.app')

@section('title', 'WELCOME | TeRMinal')

@section('content')
    <!-- Jumbotron Section -->
    <div id="jumbotron" class="container-jumbo">
        <div class="jumbo-text">
            <h2 class="mb-3">Connecting Ideas, Showcasing Talent. This is TeRMinal.</h2>
            <h6>Jadilah bagian dari program studi Teknologi Rekayasa Multimedia dan pamerkan karyamu di TeRMinal, galeri
                digital kebanggaan kami.</h6>

        </div>
        <div class="jumbo-button">
            @auth
                <a href="{{ route('posts.index') }}" class="btn-custom upload-button">Upload</a>
            @else
                <a href="{{ route('login') }}" class="btn-custom upload-button">Login to Upload</a>
            @endauth
            <a href="{{ route('posts.index') }}" class="btn-custom explore-button">Explore</a>
        </div>
    </div>

    <!-- Category Filter & Gallery Section -->
    <div class="container-gallery">
        <div class="container">
            <!-- Filter Section -->
            <div class="filter-section">
                <!-- Category Filter Tags -->
                <div class="filter-group">
                    <h6 class="filter-title">Categories</h6>
                    <div class="category-filter">
                        <button class="category-tag {{ $categoryFilter === 'all' ? 'active' : '' }}" data-category="all">
                            <span class="tag-icon">📂</span>
                            <span class="tag-text">All</span>
                        </button>

                        @if(isset($availableCategories))
                            @foreach($availableCategories as $category)
                                <a href="{{ route('posts.category', $category->slug) }}">
                                    <button class="category-tag {{ $categoryFilter === $category->slug ? 'active' : '' }}"
                                        data-category="{{ $category->slug }}">
                                        <span class="tag-icon">{{ $category->icon ?? '📁' }}</span>
                                        <span class="tag-text">{{ $category->name }}</span>
                                        @if(isset($category->posts_count))
                                            <span class="tag-count">{{ $category->posts_count }}</span>
                                        @endif
                                    </button>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Popular Hashtags -->
                @if(isset($popularHashtags) && $popularHashtags->count() > 0)
                    <div class="filter-group">
                        <h6 class="filter-title">Popular Hashtags</h6>
                        <div class="hashtag-filter">
                            @foreach($popularHashtags as $hashtag)
                                <button class="hashtag-tag {{ $hashtagFilter === $hashtag->slug ? 'active' : '' }}"
                                    data-hashtag="{{ $hashtag->slug }}">
                                    <span class="tag-text">#{{ $hashtag->name }}</span>
                                    <span class="tag-count">{{ $hashtag->posts_count }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Active Filters Display -->
                <div class="active-filters" id="active-filters" style="display: none;">
                    <span class="filter-label">Active filters:</span>
                    <div class="filter-chips"></div>
                    <button class="clear-filters" id="clear-filters">Clear All</button>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="loading-indicator" id="loading-indicator" style="display: none;">
                <div class="spinner"></div>
                <span>Loading posts...</span>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="gallery-grid">
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
                                $previewUrl = $post->file_url;
                                $mediaType = $post->file_type;
                            }

                            // Default placeholder if no media
                            if (!$previewUrl) {
                                $previewUrl = '/assets/placeholder.jpg';
                            }

                            // Get category display from database
                            $categoryDisplay = null;
                            if ($post->category) {
                                $categoryDisplay = [
                                    'slug' => $post->category->slug,
                                    'name' => $post->category->name,
                                    'icon' => $post->category->icon
                                ];
                            } elseif ($post->categories && $post->categories->count() > 0) {
                                $firstCategory = $post->categories->first();
                                $categoryDisplay = [
                                    'slug' => $firstCategory->slug,
                                    'name' => $firstCategory->name,
                                    'icon' => $firstCategory->icon
                                ];
                            }

                            // Fallback to media type if no categories
                            if (!$categoryDisplay && $mediaType) {
                                $categoryDisplay = [
                                    'slug' => $mediaType === 'image' ? 'foto' : $mediaType,
                                    'name' => $mediaType === 'image' ? 'Foto' : ucfirst($mediaType),
                                    'icon' => $mediaType === 'image' ? '📸' : ($mediaType === 'video' ? '🎬' : '🎵')
                                ];
                            }
                        @endphp

                        <div class="gallery-item" data-category="{{ $categoryDisplay['slug'] ?? 'all' }}"
                            data-hashtags="{{ $post->hashtags->pluck('slug')->implode(',') }}">
                            <a href="{{ route('posts.show', $post) }}">
                                <div class="gallery-card">
                                    @if($mediaType === 'video')
                                        <div class="media-preview video-preview">
                                            <video muted preload="metadata">
                                                <source src="{{ $previewUrl }}" type="video/mp4">
                                            </video>
                                            <div class="play-overlay">
                                                <i class="fas fa-play-circle"></i>
                                            </div>
                                            <div class="media-type-badge">
                                                <i class="fas fa-video"></i>
                                            </div>
                                        </div>
                                    @elseif($mediaType === 'audio')
                                        <div class="media-preview audio-preview">
                                            <i class="fas fa-music fa-3x"></i>
                                            <div class="audio-waves">
                                                <span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <div class="media-type-badge">
                                                <i class="fas fa-music"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="media-preview image-preview">
                                            <img src="{{ $previewUrl }}" alt="{{ $post->title }}" loading="lazy"
                                                onerror="this.src='/assets/placeholder.jpg'">
                                            @if($mediaType === 'image')
                                                <div class="media-type-badge">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="card-info">
                                        <div class="card-header">
                                            @if($categoryDisplay)
                                                <div class="category-badge">
                                                    <span class="category-icon">{{ $categoryDisplay['icon'] }}</span>
                                                    <span class="category-name">{{ $categoryDisplay['name'] }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <h5>{{ Str::limit($post->title, 40) }}</h5>

                                        @if($post->hashtags && $post->hashtags->count() > 0)
                                            <div class="hashtags">
                                                @foreach($post->hashtags->take(3) as $hashtag)
                                                    <span class="hashtag">#{{ $hashtag->name }}</span>
                                                @endforeach
                                                @if($post->hashtags->count() > 3)
                                                    <span class="hashtag-more">+{{ $post->hashtags->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="card-meta">
                                            <span class="author">{{ $post->user->name }}</span>
                                            <div class="stats">
                                                <span><i class="fas fa-heart"></i> {{ $post->likes_count ?? 0 }}</span>
                                                <span><i class="fas fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
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
                        <h4>No posts found</h4>
                        <p>Try adjusting your filters or be the first to share your creativity!</p>
                        @auth
                            <a href="{{ route('posts.create') }}" class="btn-custom mt-3">Upload Your Work</a>
                        @endauth
                    </div>
                @endif
            </div>

            <!-- Load More Button -->
            @if(isset($posts) && $posts->count() >= 24)
                <div class="load-more-container">
                    <button class="load-more-btn" id="load-more-btn">
                        <span>Load More Posts</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Previous styles remain the same, plus new additions */

        /* Jumbotron Styles */

        #jumbotron {
            background-image: 'resources\img\JUMBOTRON_IMAGE.jpg';
            /* background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 40%); */
            /* background: radial-gradient(circle, #9D84B7 0%, transparent 40%); */
        }

        .container-jumbo {
            background: linear-gradient(rgba(157, 132, 183, 0.5), rgba(212, 165, 211, 0.5)), ;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 200px 0;
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
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
            padding: 12px 25px;
            min-width: 165px;
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
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .upload-button {
            background-color: white;
            color: #9D84B7;
            border: 1px solid white;
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

        /* Gallery Section */
        .container-gallery {
            padding: 40px 0;
            background: linear-gradient(180deg, #FFFFFF, #F8F9FA);
            min-height: 500px;
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 40px;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Category Filter */
        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .category-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
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
            transform: translateY(-1px);
        }

        .category-tag.active {
            background-color: #9D84B7;
            color: white;
            border-color: #9D84B7;
        }

        .tag-icon {
            font-size: 16px;
        }

        .tag-count {
            background-color: rgba(0, 0, 0, 0.1);
            color: currentColor;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 4px;
        }

        .category-tag.active .tag-count {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Hashtag Filter */
        .hashtag-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            padding: 0 20px;
        }

        .hashtag-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            border-radius: 15px;
            font-size: 13px;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        .hashtag-tag:hover {
            border-color: #9D84B7;
            background-color: #9D84B7;
            color: white;
        }

        .hashtag-tag.active {
            background-color: #9D84B7;
            color: white;
            border-color: #9D84B7;
        }

        /* Active Filters */
        .active-filters {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 10px;
            margin: 20px;
        }

        .filter-label {
            font-weight: 600;
            color: #1976d2;
        }

        .filter-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background-color: #1976d2;
            color: white;
            border-radius: 12px;
            font-size: 12px;
        }

        .clear-filters {
            background: none;
            border: 1px solid #1976d2;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clear-filters:hover {
            background-color: #1976d2;
            color: white;
        }

        /* Loading Indicator */
        .loading-indicator {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #9D84B7;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .media-preview {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
            background-color: #f0f0f0;
        }

        .media-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 12px;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-card:hover .image-preview img {
            transform: scale(1.05);
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
            transition: opacity 0.3s ease;
        }

        .gallery-card:hover .play-overlay {
            opacity: 1;
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

        .audio-waves span:nth-child(2) {
            animation-delay: -1.1s;
        }

        .audio-waves span:nth-child(3) {
            animation-delay: -1.0s;
        }

        .audio-waves span:nth-child(4) {
            animation-delay: -0.9s;
        }

        .audio-waves span:nth-child(5) {
            animation-delay: -0.8s;
        }

        @keyframes wave {

            0%,
            100% {
                transform: scaleY(0.5);
            }

            50% {
                transform: scaleY(1);
            }
        }

        /* Card Info */
        .card-info {
            padding: 20px;
        }

        .card-header {
            margin-bottom: 10px;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            color: #666;
            border: 1px solid #e0e0e0;
        }

        .category-icon {
            font-size: 12px;
        }

        .card-info h5 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
            line-height: 1.3;
        }

        .hashtags {
            margin-bottom: 10px;
        }

        .hashtag {
            display: inline-block;
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 11px;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .hashtag-more {
            display: inline-block;
            background-color: #f0f0f0;
            color: #666;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 11px;
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

        /* Load More */
        .load-more-container {
            text-align: center;
            margin-top: 40px;
        }

        .load-more-btn {
            background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .load-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(157, 132, 183, 0.3);
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
                margin: 5px 0;
            }

            .category-filter,
            .hashtag-filter {
                padding: 0 10px;
            }

            .category-tag,
            .hashtag-tag {
                font-size: 12px;
                padding: 6px 12px;
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
        document.addEventListener('DOMContentLoaded', function () {
            const categoryTags = document.querySelectorAll('.category-tag');
            const hashtagTags = document.querySelectorAll('.hashtag-tag');
            const galleryGrid = document.getElementById('gallery-grid');
            const loadingIndicator = document.getElementById('loading-indicator');
            const activeFiltersContainer = document.getElementById('active-filters');
            const clearFiltersBtn = document.getElementById('clear-filters');

            let currentFilters = {
                category: '{{ $categoryFilter ?? "all" }}',
                hashtag: '{{ $hashtagFilter ?? "" }}'
            };

            // Category filter handling
            categoryTags.forEach(tag => {
                tag.addEventListener('click', function () {
                    const selectedCategory = this.getAttribute('data-category');

                    // Update active states
                    categoryTags.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update current filter
                    currentFilters.category = selectedCategory;

                    // Apply filters
                    applyFilters();
                });
            });

            // Hashtag filter handling
            hashtagTags.forEach(tag => {
                tag.addEventListener('click', function () {
                    const selectedHashtag = this.getAttribute('data-hashtag');

                    // Toggle hashtag selection
                    if (this.classList.contains('active')) {
                        this.classList.remove('active');
                        currentFilters.hashtag = '';
                    } else {
                        hashtagTags.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                        currentFilters.hashtag = selectedHashtag;
                    }

                    // Apply filters
                    applyFilters();
                });
            });

            // Clear filters
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function () {
                    currentFilters = { category: 'all', hashtag: '' };

                    // Reset UI
                    categoryTags.forEach(tag => {
                        tag.classList.toggle('active', tag.getAttribute('data-category') === 'all');
                    });
                    hashtagTags.forEach(tag => tag.classList.remove('active'));

                    applyFilters();
                });
            }

            // Apply filters function
            function applyFilters() {
                showLoading();
                updateActiveFilters();

                // Build URL with filters
                const params = new URLSearchParams();
                if (currentFilters.category !== 'all') {
                    params.append('category', currentFilters.category);
                }
                if (currentFilters.hashtag) {
                    params.append('hashtag', currentFilters.hashtag);
                }

                // Fetch filtered posts
                fetch(`/filter-posts?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        renderPosts(data.posts);
                        hideLoading();

                        // Update URL without page reload
                        const newUrl = params.toString() ? `?${params.toString()}` : window.location.pathname;
                        window.history.pushState({}, '', newUrl);
                    })
                    .catch(error => {
                        console.error('Error filtering posts:', error);
                        hideLoading();
                    });
            }

            // Show loading indicator
            function showLoading() {
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'block';
                }
                galleryGrid.style.opacity = '0.5';
            }

            // Hide loading indicator
            function hideLoading() {
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
                galleryGrid.style.opacity = '1';
            }

            // Update active filters display
            function updateActiveFilters() {
                if (!activeFiltersContainer) return;

                const hasActiveFilters = currentFilters.category !== 'all' || currentFilters.hashtag;

                if (hasActiveFilters) {
                    const filterChips = activeFiltersContainer.querySelector('.filter-chips');
                    filterChips.innerHTML = '';

                    if (currentFilters.category !== 'all') {
                        const categoryName = document.querySelector(`[data-category="${currentFilters.category}"]`)?.textContent?.trim() || currentFilters.category;
                        const chip = document.createElement('span');
                        chip.className = 'filter-chip';
                        chip.innerHTML = `Category: ${categoryName}`;
                        filterChips.appendChild(chip);
                    }

                    if (currentFilters.hashtag) {
                        const chip = document.createElement('span');
                        chip.className = 'filter-chip';
                        chip.innerHTML = `#${currentFilters.hashtag}`;
                        filterChips.appendChild(chip);
                    }

                    activeFiltersContainer.style.display = 'flex';
                } else {
                    activeFiltersContainer.style.display = 'none';
                }
            }

            // Render posts
            function renderPosts(posts) {
                if (posts.length === 0) {
                    galleryGrid.innerHTML = `
                                                                                        <div class="empty-state">
                                                                                            <i class="fas fa-search fa-4x mb-3"></i>
                                                                                            <h4>No posts found</h4>
                                                                                            <p>Try adjusting your filters or explore different categories!</p>
                                                                                        </div>
                                                                                    `;
                    return;
                }

                galleryGrid.innerHTML = posts.map(post => {
                    const mediaTypeIcons = {
                        'video': 'fas fa-video',
                        'audio': 'fas fa-music',
                        'image': 'fas fa-image'
                    };

                    let mediaPreview = '';
                    if (post.media_type === 'video') {
                        mediaPreview = `
                                                                                            <div class="media-preview video-preview">
                                                                                                <video muted preload="metadata">
                                                                                                    <source src="${post.preview_url}" type="video/mp4">
                                                                                                </video>
                                                                                                <div class="play-overlay">
                                                                                                    <i class="fas fa-play-circle"></i>
                                                                                                </div>
                                                                                                <div class="media-type-badge">
                                                                                                    <i class="${mediaTypeIcons[post.media_type]}"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
                    } else if (post.media_type === 'audio') {
                        mediaPreview = `
                                                                                            <div class="media-preview audio-preview">
                                                                                                <i class="fas fa-music fa-3x"></i>
                                                                                                <div class="audio-waves">
                                                                                                    <span></span><span></span><span></span><span></span><span></span>
                                                                                                </div>
                                                                                                <div class="media-type-badge">
                                                                                                    <i class="${mediaTypeIcons[post.media_type]}"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
                    } else {
                        mediaPreview = `
                                                                                            <div class="media-preview image-preview">
                                                                                                <img src="${post.preview_url}" alt="${post.title}" loading="lazy" onerror="this.src='/assets/placeholder.jpg'">
                                                                                                ${post.media_type === 'image' ? `<div class="media-type-badge"><i class="${mediaTypeIcons[post.media_type]}"></i></div>` : ''}
                                                                                            </div>
                                                                                        `;
                    }

                    return `
                                                                                        <div class="gallery-item">
                                                                                            <a href="${post.url}">
                                                                                                <div class="gallery-card">
                                                                                                    ${mediaPreview}
                                                                                                    <div class="card-info">
                                                                                                        <div class="card-header">
                                                                                                            ${post.category ? `
                                                                                                                <div class="category-badge">
                                                                                                                    <span class="category-icon">${post.category.icon}</span>
                                                                                                                    <span class="category-name">${post.category.name}</span>
                                                                                                                </div>
                                                                                                            ` : ''}
                                                                                                        </div>
                                                                                                        <h5>${post.title.length > 40 ? post.title.substring(0, 40) + '...' : post.title}</h5>
                                                                                                        <div class="card-meta">
                                                                                                            <span class="author">${post.user.name}</span>
                                                                                                            <div class="stats">
                                                                                                                <span><i class="fas fa-heart"></i> ${post.stats.likes_count}</span>
                                                                                                                <span><i class="fas fa-comment"></i> ${post.stats.comments_count}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </a>
                                                                                        </div>
                                                                                    `;
                }).join('');
            }

            // Initialize active filters display
            updateActiveFilters();
    </script>
@endsection