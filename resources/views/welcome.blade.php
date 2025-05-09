<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Polars - Ruang pamer virtual mahasiswa Teknologi Rekayasa Multimedia">
    <title>WELCOME | TeRMinal</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Navbar - Menggunakan class Bootstrap -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <!-- Logo Kiri -->
            <a href="/" class="navbar-brand">
                <img src="assets/terminallogo.png" alt="PoLaRs." class="navbar-logo-polars">
            </a>

            <!-- Toggle Button untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Tengah -->
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

                <!-- Search Bar -->
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

    <!-- Jumbotron Section -->
    <div class="container-jumbo">
        <div class="jumbo-text">
            <h2 class="mb-3">Showcase Your Creativity with Polars</h2>
            <h6>Join the Teknologi Rekayasa Multimedia program and display your projects in our vibrant student gallery.</h6>
        </div>
        <div class="jumbo-button">
            <a href="upload" class="btn-custom upload-button">Upload</a>
            <a href="gotoabout" class="btn-custom explore-button">Explore</a>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="container-welcome py-5">
        <div class="welcome-text">
            <h1 class="mb-4">Welcome!</h1>
            <h4 class="mb-3">Ruang pamer virtual mahasiswa dan dosen Program Studi Teknologi Rekayasa Multimedia.</h4>
            <h5 class="fw-bold mb-4">Let's inspire and be inspired!</h5>
            <a href="getstarted" class="btn-custom welcome-button">Get Started</a>
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

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>