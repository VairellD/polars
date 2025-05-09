<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your Space - Profile Page">
    <title>YOUR SPACE</title>
    <!-- Bootstrap CSS harus dimuat sebelum custom CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo Kiri -->
            <a href="/" class="navbar-brand">
                <img src="assets/terminallogo.png" alt="PoLaRs." class="navbar-logo-polars">
            </a>

            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu dan Search -->
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

                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>

            <!-- Logo Kanan -->
            <a href="#" class="navbar-brand ms-3 d-none d-lg-block">
                <img src="assets/himedia logo 1.png" alt="HiMedia" class="logo-himedia">
            </a>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="container py-4">
        <!-- Tambahkan konten profile di sini -->
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>