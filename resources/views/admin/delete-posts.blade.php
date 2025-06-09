@extends('layouts.app')

@section('title', 'Delete Posts | Admin')

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
                <a href="{{ route('admin.delete-posts') }}" class="list-group-item list-group-item-action active">
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
                <div>
                    <h2 class="mb-0">Manage Posts</h2>
                    <p class="text-muted mb-0">Delete and manage user posts</p>
                </div>
                <span class="badge bg-danger">ADMIN</span>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" id="select-all" class="form-check-input me-2">
                            <label for="select-all" class="form-check-label">Select All</label>
                        </div>
                        <div>
                            <button type="button" id="delete-selected" class="btn btn-danger" disabled>
                                <i class="bi bi-trash"></i> Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="row">
                @forelse($posts as $post)
                    <div class="col-md-6 col-xl-4 mb-4" data-post-id="{{ $post->id }}">
                        <div class="card h-100 position-relative">
                            <!-- Checkbox -->
                            <div class="position-absolute" style="top: 10px; left: 10px; z-index: 10;">
                                <input type="checkbox" class="form-check-input post-checkbox" value="{{ $post->id }}">
                            </div>

                            <!-- Media Preview -->
                            @if($post->file_url)
                                <div style="height: 200px; overflow: hidden;">
                                    @if($post->file_type == 'image')
                                        <img src="{{ $post->file_url }}" class="card-img-top" style="height: 100%; object-fit: cover;" alt="Post image">
                                    @elseif($post->file_type == 'video')
                                        <video class="card-img-top" style="height: 100%; object-fit: cover;" muted>
                                            <source src="{{ $post->file_url }}" type="video/{{ $post->file_extension }}">
                                        </video>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="bi bi-play-circle-fill text-white" style="font-size: 3rem; opacity: 0.8;"></i>
                                        </div>
                                    @elseif($post->file_type == 'audio')
                                        <div class="bg-gradient-primary d-flex align-items-center justify-content-center h-100 text-white">
                                            <i class="bi bi-music-note-beamed" style="font-size: 3rem;"></i>
                                        </div>
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                            <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($post->title, 50) }}</h6>
                                <p class="card-text small text-muted">{{ Str::limit($post->description, 80) }}</p>

                                <!-- User Info -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle overflow-hidden me-2" style="width: 30px; height: 30px;">
                                        <img src="{{ $post->user->profile_picture ?? asset('assets/default-avatar.jpg') }}"
                                             class="img-fluid" alt="{{ $post->user->name }}">
                                    </div>
                                    <small class="text-muted">{{ $post->user->name }}</small>
                                </div>

                                <!-- Stats -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-heart"></i> {{ $post->likes_count }}
                                            <i class="bi bi-chat ms-2"></i> {{ $post->comments_count }}
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-post"
                                            data-post-id="{{ $post->id }}" data-post-title="{{ $post->title }}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-text display-4 text-muted mb-3"></i>
                            <h5>No posts found</h5>
                            <p class="text-muted">All posts have been managed.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePostModalLabel">Confirm Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the post <strong id="post-title-placeholder"></strong>?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-post">Delete Post</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Multiple Posts Modal -->
<div class="modal fade" id="deleteMultiplePostsModal" tabindex="-1" aria-labelledby="deleteMultiplePostsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultiplePostsModalLabel">Confirm Delete Multiple Posts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="selected-posts-count"></strong> selected posts?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-multiple-posts">Delete Posts</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-post');
    const deleteSelectedBtn = document.getElementById('delete-selected');
    const selectAllCheckbox = document.getElementById('select-all');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');

    let currentPostId = null;
    let selectedPostIds = [];

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            postCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Individual checkbox handling
    postCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
        selectedPostIds = Array.from(checkedBoxes).map(cb => cb.value);

        if (deleteSelectedBtn) {
            deleteSelectedBtn.disabled = selectedPostIds.length === 0;
        }
    }

    // Single post delete
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentPostId = this.getAttribute('data-post-id');
            const postTitle = this.getAttribute('data-post-title');

            document.getElementById('post-title-placeholder').textContent = postTitle;

            const deleteModal = new bootstrap.Modal(document.getElementById('deletePostModal'));
            deleteModal.show();
        });
    });

    // Multiple posts delete
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function() {
            if (selectedPostIds.length > 0) {
                document.getElementById('selected-posts-count').textContent = selectedPostIds.length;

                const deleteMultipleModal = new bootstrap.Modal(document.getElementById('deleteMultiplePostsModal'));
                deleteMultipleModal.show();
            }
        });
    }

    // Confirm single delete
    document.getElementById('confirm-delete-post').addEventListener('click', function() {
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
                    // Remove card from grid
                    document.querySelector(`[data-post-id="${currentPostId}"]`).remove();

                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }

                bootstrap.Modal.getInstance(document.getElementById('deletePostModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while deleting the post.');
                bootstrap.Modal.getInstance(document.getElementById('deletePostModal')).hide();
            });
        }
    });

    // Confirm multiple delete
    document.getElementById('confirm-delete-multiple-posts').addEventListener('click', function() {
        if (selectedPostIds.length > 0) {
            fetch('/admin/posts/bulk-delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    post_ids: selectedPostIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove cards from grid
                    selectedPostIds.forEach(postId => {
                        const card = document.querySelector(`[data-post-id="${postId}"]`);
                        if (card) card.remove();
                    });

                    // Reset checkboxes
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    updateDeleteButton();

                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }

                bootstrap.Modal.getInstance(document.getElementById('deleteMultiplePostsModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while deleting posts.');
                bootstrap.Modal.getInstance(document.getElementById('deleteMultiplePostsModal')).hide();
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
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .list-group-item.active {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #9D84B7 0%, #D4A5D3 100%);
    }
</style>
@endsection
