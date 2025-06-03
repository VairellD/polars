<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <!-- Logo Kiri -->
        <a href="/" class="navbar-brand">
            <img src="{{ asset('assets/terminallogo.png') }}" alt="PoLaRs." class="navbar-logo-polars">
        </a>

        <!-- Toggle Button untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Tengah -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('posts*') ? 'active' : '' }}" href="{{ route('posts.index') }}">GALLERY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('profile.index') }}">PROFILE</a>
                </li>
            </ul>

            <!-- Search Bar -->
            <form class="d-flex" action="{{ route('posts.index') }}" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
        </div>

        <!-- Logo Kanan with Authentication Dropdown -->
        <div class="position-relative logo-auth-container ms-3">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('assets/himedia logo 1.png') }}" alt="HiMedia" class="logo-himedia">
            </a>
            
            <!-- Hidden Dropdown that appears on hover -->
            <div class="auth-dropdown">
                @auth
                    <a href="{{ route('profile.index') }}" class="auth-dropdown-item">
                        <i class="bi bi-person-circle"></i> My Profile
                    </a>
                    <a href="{{ route('posts.create') }}" class="auth-dropdown-item">
                        <i class="bi bi-upload"></i> Upload
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="auth-dropdown-item">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="auth-dropdown-item">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>