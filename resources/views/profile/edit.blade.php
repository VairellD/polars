@extends('layouts.app')

@section('title', 'Edit Profile | Polars')

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
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Profile Edit -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 150px; height: 150px;">
                                    <img src="{{ Auth::user()->profile_picture ?? asset('assets/default-avatar.jpg') }}" 
                                         id="profile-picture-preview" class="img-fluid" alt="Profile Picture">
                                </div>
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label visually-hidden">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                    @error('profile_picture')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                               id="username" name="username" value="{{ old('username', Auth::user()->username) }}">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              id="bio" name="bio" rows="3">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="url" class="form-label">Website</label>
                                    <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                           id="url" name="url" value="{{ old('url', Auth::user()->url) }}">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Password Update -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Update Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Delete Account -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0 text-danger">Delete Account</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            Delete Account
                        </button>
                    </div>
                    
                    <!-- Delete Account Modal -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                                    <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" 
                                                   id="password" name="password" placeholder="Enter your password to confirm">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" form="delete-account-form" class="btn btn-danger">Delete Account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview profile picture before upload
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profile_picture');
        const profilePicturePreview = document.getElementById('profile-picture-preview');
        
        if (profilePictureInput && profilePicturePreview) {
            profilePictureInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        profilePicturePreview.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection