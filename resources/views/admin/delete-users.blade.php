@extends('layouts.app')

@section('title', 'Delete Users | Admin')

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
                <a href="{{ route('admin.delete-users') }}" class="list-group-item list-group-item-action active">
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
                <div>
                    <h2 class="mb-0">Manage Users</h2>
                    <p class="text-muted mb-0">Delete and manage user accounts</p>
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

            <!-- Users List -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50px">
                                            <input type="checkbox" id="header-checkbox" class="form-check-input">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Posts</th>
                                        <th>Joined</th>
                                        <th width="100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr data-user-id="{{ $user->id }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle overflow-hidden me-3" style="width: 40px; height: 40px;">
                                                        <img src="{{ $user->profile_picture ?? asset('assets/default-avatar.jpg') }}"
                                                             class="img-fluid" alt="{{ $user->name }}">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ '@' . $user->username }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $user->posts_count }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-user"
                                                        data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people display-4 text-muted mb-3"></i>
                            <h5>No users found</h5>
                            <p class="text-muted">All users have been managed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="user-name-placeholder"></strong>?</p>
                <p class="text-danger"><strong>Warning:</strong> This action will also delete all posts created by this user and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Multiple Users Modal -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultipleModalLabel">Confirm Delete Multiple Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="selected-count"></strong> selected users?</p>
                <p class="text-danger"><strong>Warning:</strong> This action will also delete all posts created by these users and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-multiple">Delete Users</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-user');
    const deleteSelectedBtn = document.getElementById('delete-selected');
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const headerCheckbox = document.getElementById('header-checkbox');

    let currentUserId = null;
    let selectedUserIds = [];

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    if (headerCheckbox) {
        headerCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Individual checkbox handling
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        selectedUserIds = Array.from(checkedBoxes).map(cb => cb.value);

        if (deleteSelectedBtn) {
            deleteSelectedBtn.disabled = selectedUserIds.length === 0;
        }
    }

    // Single user delete
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentUserId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');

            document.getElementById('user-name-placeholder').textContent = userName;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            deleteModal.show();
        });
    });

    // Multiple users delete
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function() {
            if (selectedUserIds.length > 0) {
                document.getElementById('selected-count').textContent = selectedUserIds.length;

                const deleteMultipleModal = new bootstrap.Modal(document.getElementById('deleteMultipleModal'));
                deleteMultipleModal.show();
            }
        });
    }

    // Confirm single delete
    document.getElementById('confirm-delete').addEventListener('click', function() {
        if (currentUserId) {
            fetch(`/admin/users/${currentUserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove row from table
                    document.querySelector(`tr[data-user-id="${currentUserId}"]`).remove();

                    // Show success message
                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }

                bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while deleting the user.');
                bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
            });
        }
    });

    // Confirm multiple delete
    document.getElementById('confirm-delete-multiple').addEventListener('click', function() {
        if (selectedUserIds.length > 0) {
            fetch('/admin/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_ids: selectedUserIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove rows from table
                    selectedUserIds.forEach(userId => {
                        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                        if (row) row.remove();
                    });

                    // Reset checkboxes
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    if (headerCheckbox) headerCheckbox.checked = false;
                    updateDeleteButton();

                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }

                bootstrap.Modal.getInstance(document.getElementById('deleteMultipleModal')).hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while deleting users.');
                bootstrap.Modal.getInstance(document.getElementById('deleteMultipleModal')).hide();
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

        // Insert at the top of the main content
        const mainContent = document.querySelector('.col-lg-9');
        mainContent.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto dismiss after 5 seconds
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
    .table th {
        border-top: none;
        font-weight: 600;
    }

    .card {
        border-radius: 12px;
    }

    .list-group-item.active {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
</style>
@endsection
