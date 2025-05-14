@extends('layouts.app')

@section('title', 'WELCOME | TeRMinal')

@section('content')
    <!-- Jumbotron Section -->
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

    <!-- Welcome Section -->
    <div class="container-welcome py-5">
        <div class="welcome-text">
            <h1 class="mb-4">Welcome!</h1>
            <h4 class="mb-3">Ruang pamer virtual mahasiswa dan dosen Program Studi Teknologi Rekayasa Multimedia.</h4>
            <h5 class="fw-bold mb-4">Let's inspire and be inspired!</h5>
            <a href="{{ route('posts.index') }}" class="btn-custom welcome-button">Get Started</a>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="container-inside">
        <div class="container">
            <div class="inside-text mx-auto">
                <h1 class="mb-4">What's Inside?</h1>
                <h6>Banyak jenis karya seperti: website, animasi, game, desain, dan lainnya.</h6>
            </div>

            <!-- Grid Gallery -->
            <div class="row g-4">
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/videografi.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Videography</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/website.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Website</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/game.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Game</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/logodesign.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Logo Design</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/photography.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Photography</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/illustration.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Illustration</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/bgawal.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">Editing</div>
                    </div>
                </div>
                <div class="col">
                    <div class="image-container">
                        <img src="/assets/uiux.jpg" class="img-fluid img-thumbnail" alt="...">
                        <div class="overlay">UI/UX</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Custom styles for the auth dropdown */
    .logo-auth-container {
        position: relative;
    }

    .auth-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 200px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
        z-index: 1000;
        overflow: hidden;
    }

    .logo-auth-container:hover .auth-dropdown {
        display: block;
    }

    .auth-dropdown-item {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.2s;
        border: none;
        width: 100%;
        text-align: left;
        background: transparent;
    }

    .auth-dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 0;
    }
</style>
@endsection
