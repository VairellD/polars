<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeRMinal | Gallery</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo Kiri -->
            <a href="/" class="navbar-brand">
                <img src="{{ asset('assets/terminallogo.png') }}" alt="PoLaRs." class="navbar-logo-polars">
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gotoabout">GALLERY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile">PROFILE</a>
                    </li>
                </ul>

                <!-- Search -->
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>

            <!-- Logo Kanan -->
            <a href="#" class="navbar-brand ms-3">
                <img src="assets/himedia logo 1.png" alt="HiMedia" class="logo-himedia">
            </a>
        </div>
    </nav>

    <!-- Category Preview Section -->
    <div class="category-preview py-4">
        <div class="container">
            <h4 class="mb-4">Categories</h4>
            <!-- Bootstrap Carousel -->
            <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- First Slide -->
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/videografi.jpg" class="img-fluid rounded" alt="Videography">
                                    <div class="overlay">Videography</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/website.jpg" class="img-fluid rounded" alt="Website">
                                    <div class="overlay">Website</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/game.jpg" class="img-fluid rounded" alt="Game">
                                    <div class="overlay">Game</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/logodesign.jpg" class="img-fluid rounded" alt="Logo Design">
                                    <div class="overlay">Logo Design</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Second Slide -->
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/photography.jpg" class="img-fluid rounded" alt="Photography">
                                    <div class="overlay">Photography</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/illustration.jpg" class="img-fluid rounded" alt="Illustration">
                                    <div class="overlay">Illustration</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/editing.jpg" class="img-fluid rounded" alt="Editing">
                                    <div class="overlay">Editing</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="image-container">
                                    <img src="/assets/uiux.jpg" class="img-fluid rounded" alt="UI/UX">
                                    <div class="overlay">UI/UX</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Gallery Header -->
    <div class="gallery-header">
        <div class="container py-4">
            <div class="d-flex justify-content-center">
                <div class="gallery-filters">
                    <button class="btn btn-filter active">Discover All</button>
                    <button class="btn btn-filter">Recent</button>
                    <button class="btn btn-filter">Editor Picks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="container">
        <div class="gallery-grid">
            <!-- Contoh item gallery -->
            <div class="gallery-card">
                <div class="image-container">
                    <img src="path/to/image1.jpg" alt="Project 1">
                    <div class="overlay">
                        <span>Project 1</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Judul Project</h5>
                    <p class="card-text">Deskripsi singkat project</p>
                    <div class="card-tags">
                        <span class="tag">Website</span>
                    </div>
                </div>
            </div>
            <!-- Tambahkan card lainnya sesuai kebutuhan -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
