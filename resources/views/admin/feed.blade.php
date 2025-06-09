@extends('layouts.app')

@section('title', 'Admin Feed | Polars')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-3">
            <div class="list-group sticky-top" style="top: 20px">
                <a href="{{ route('posts.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-house-door me-2"></i> Home
                </a>
                <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person-gear me-2"></i> Admin Profile
                </a>
                <a href="{{ route('admin.delete-users') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-people me-2"></i> Delete Users
                </a>
                <a href="{{ route('admin.delete-posts') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-file-earmark-x me-2"></i> Delete Posts
                </a>
                <a href="{{ route('admin.feed') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-rss me-2"></i> Admin Feed
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Admin Feed</h2>
                    <p class="text-muted mb-0">Monitor all platform activity</p>
                </div>
                <span class="badge bg-danger">ADMIN</span>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-text-fill display-4 mb-3"></i>
                            <h3 class="mb-1">{{ $posts->total() }}</h3>
                            <p class="mb-0">Total Posts</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-heart-fill display-4 mb-3"></i>
                            <h3 class="mb-1">{{ $posts->sum('likes_count') }}</h3>
                            <p class="mb-0">Total Likes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-white border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-chat-fill display-4 mb-3"></i>
                            <h3 class="mb-1">{{ $posts->sum('comments_count') }}</h3>
                            <p class="mb-0">Total Comments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Feed -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Recent Posts Activity</h5>
                </div>
                <div class="card-body p-0">
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                            <div class="border-bottom p-4">
                                <div class="row">
                                    <!-- Post Media -->
                                    <div class="col-md-3">
                                        @if($post->file_url)
                                            <div class="rounded overflow-hidden" style="height: 120px;">
                                                @if($post->file_type == 'image')
                                                    <img src="{{ $post->file_url }}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Post image">
                                                @elseif($post->file_type == 'video')
                                                    <div class="position-relative h-100">
                                                        <video class="w-100 h-100" style="object-fit: cover;" muted>
                                                            <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                                                        </video>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="bi bi-play-circle-fill text-white" style="font-size: 2rem;"></i>
                                                        </div>
                                                    </div>
                                                @elseif($post->file_type == 'audio')
                                                    <div class="bg-gradient-primary d-flex align-items-center justify-content-center h-100 text-white rounded">
                                                        <i class="bi bi-music-note-beamed" style="font-size: 2.5rem;"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded">
                                                        <i class="bi bi-file-earmark text-muted" style="font-size: 2.5rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 120px;">
                                                <i class="bi bi-file-earmark-text text-muted" style="font-size: 2.5rem;"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Post Info -->
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle overflow-hidden me-3" style="width: 40px; height: 40px;">
                                                    <img src="{{ $post->user->profile_picture ?? asset('assets/default-avatar.jpg') }}"
                                                         class="img-fluid" alt="{{ $post->user->name }}">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
                                                    <small class="text-muted">{{ '@' . $post->user->username }} • {{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('posts.show', $post) }}"><i class="bi bi-eye me-2"></i>View Post</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('profile.show', $post->user) }}"><i class="bi bi-person me-2"></i>View Profile</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger delete-post-feed"
                                                                data-post-id="{{ $post->id }}" data-post-title="{{ $post->title }}">
                                                            <i class="bi bi-trash me-2"></i>Delete Post
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <h5 class="mb-2">{{ $post->title }}</h5>
                                        <p class="text-muted mb-3">{{ Str::limit($post->description, 150) }}</p>

                                        <!-- Post Stats -->
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex gap-4">
                                                <span class="text-muted">
                                                    <i class="bi bi-heart text-danger"></i> {{ $post->likes_count }}
                                                </span>
                                                <span class="text-muted">
                                                    <i class="bi bi-chat text-primary"></i> {{ $post->comments_count }}
                                                </span>
                                                @if($post->file_type)
                                                    <span class="badge bg-secondary">
                                                        @if($post->file_type == 'image')
                                                            <i class="bi bi-image me-1"></i>Image
                                                        @elseif($post->file_type == 'video')
                                                            <i class="bi bi-play me-1"></i>Video
                                                        @elseif($post->file_type == 'audio')
                                                            <i class="bi bi-music-note me-1"></i>Audio
                                                        @else
                                                            <i class="bi bi-file-earmark me-1"></i>File
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($posts->hasPages())
                            <div class="p-4">
                                <div class="d-flex justify-content-center">
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-rss display-4 text-muted mb-3"></i>
                            <h5>No posts in feed</h5>
                            <p class="text-muted">No posts have been created yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Post Modal (Feed) -->
<div class="modal fade" id="deletePostFeedModal" tabindex="-1" aria-labelledby="deletePostFeedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePostFeedModalLabel">Confirm Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the post <strong id="post-title-feed-placeholder"></strong>?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-post-feed">Delete Post</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-post-feed');
    let currentPostId = null;

    // Single post delete from feed
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentPostId = this.getAttribute('data-post-id');
            const postTitle = this.getAttribute('data-post-title');

            document.getElementById('post-title-feed-placeholder').textContent = postTitle;

            const deleteModal = new bootstrap.Modal(document.getElementById('deletePostFeedModal'));
            deleteModal.show();
        });
    });

    // Confirm delete from feed
    document.getElementById('confirm-delete-post-feed').addEventListener('click', function() {
        if (currentPostId) {
            fetch(`/admin/posts/${currentPostId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh page to update feed
                    location.reload();
                } else {
                    showAlert('danger', data.message);
                }

                bootstrap.Modal.getInstance(document.getElementById('deletePostFeedModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while deleting the post.');
                bootstrap.Modal.getInstance(document.getElementById('deletePostFeedModal')).hide();
            });
        }
    });

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        const mainContent = document.querySelector('.col-lg-9');
        mainContent.insertAdjacentHTML('afterbegin', alertHtml);

        setTimeout(() => {
            const alert = mainContent.querySelector('.alert');
            if (alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 5000);
    }
});
</script>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
    }

    .list-group-item.active {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 100%);
    }

    .border-bottom:last-child {
        border-bottom: none !important;
    }

    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
@endsection
