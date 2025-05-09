<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login page">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6b46c1;
            --primary-dark: #553c9a;
            --bg-color: #f0f2f5;
            --transition-default: all 0.3s ease;
        }

        body {
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 400px;
            transition: var(--transition-default);
            background: rgba(255, 255, 255, 0.98);
            border-radius: 10px;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            transition: var(--transition-default);
        }

        .logo-circle:hover {
            transform: scale(1.1);
            background: var(--primary-dark);
        }

        .form-control {
            transition: var(--transition-default);
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(107, 70, 193, 0.25);
            transform: translateY(-2px);
        }

        .btn-purple {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transition: var(--transition-default);
            padding: 12px;
            font-weight: 500;
        }

        .btn-purple:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
            transform: scale(1.02);
        }

        .btn-purple:active {
            transform: scale(0.98);
        }

        .link-purple {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition-default);
            position: relative;
            font-weight: 500;
        }

        .link-purple:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .link-purple:hover:after {
            width: 100%;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* Tambahkan style untuk logo */
        .terminal-logo {
            position: absolute;
            top: 20px;
            left: 20px;
            transition: var(--transition-default);
        }

        .terminal-logo img {
            height: 40px;
            width: auto;
        }

        .terminal-logo:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <!-- Tambahkan logo dengan link -->
    <a href="/" class="terminal-logo">
        <img src="assets/terminallogo.png" alt="Terminal Logo">
    </a>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card login-container">
                    <div class="card-body p-4">
                        <div class="logo-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" aria-hidden="true">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                            </svg>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email"
                                placeholder="Username@gmail.com" autocomplete="email">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password"
                                placeholder="••••••••" autocomplete="current-password">
                        </div>

                        <button type="button" class="btn btn-purple w-100 mb-4">
                            Login
                        </button>

                        <div class="d-flex justify-content-between">
                            <a href="#" class="link-purple">Signup</a>
                            <a href="#" class="link-purple">Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>