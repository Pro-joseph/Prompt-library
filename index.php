<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevGenius Solutions</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        /* HERO */
        .hero {
            position: relative;
            background: url('prompt.jpg') center/cover no-repeat;
            height: 100vh; /* full screen */
            display: flex;
            align-items: center;
            justify-content: center;
        }

/* dark overlay for readability */
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
        }

/* content above overlay */
        .hero .container {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3rem;
            color: white;
        }
        .hero p {
            color: white;
        }

        /* CARDS */
        .feature-card {
            border: none;
            border-radius: 16px;
            padding: 30px;
            transition: 0.3s;
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .feature-icon {
            font-size: 32px;
            color: #0d6efd;
        }

        /* CTA */
        .cta {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            border-radius: 16px;
            padding: 60px 20px;
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(10px);
        }
        .navbar {
            background: transparent !important;
            position: absolute;
            width: 100%;
            z-index: 10;
        }

        .navbar-brand {
            letter-spacing: 0.5px;
        }

        .nav-link {
            transition: 0.2s;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        .btn-primary {
            border-radius: 8px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-4" href="#">DevGenius</a>

            <!-- Right actions -->
            <div class="d-flex align-items-center gap-2">
                <a href="public/login.php" class="btn btn-outline-light btn-sm">Login</a>
                <a href="public/register.php" class="btn btn-primary btn-sm px-3">Get Started</a>
            </div>

    </div>
</nav>
<!-- HERO -->

<section class="hero text-center">
    <div class="container">
        <h1 class="fw-bold">Centralize Your AI Prompts</h1>
        <p class="lead mt-3 mb-4">
            Store, organize, and reuse powerful prompts across your team.
        </p>
        <a href="public/register.php" class="btn btn-light btn-lg px-4">Get Started</a>
    </div>
</section>

<!-- ABOUT -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Why DevGenius?</h2>
        <p class="text-muted col-lg-6 mx-auto">
            A modern platform designed for developers to manage prompt engineering efficiently and collaborate smarter.
        </p>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">

            <div class="col-md-4">
                <div class="feature-card shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h5 class="fw-bold">Smart Organization</h5>
                    <p class="text-muted">Structure prompts into clean, reusable categories.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold">Instant Search</h5>
                    <p class="text-muted">Find any prompt instantly with advanced filtering.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="fw-bold">Team Collaboration</h5>
                    <p class="text-muted">Share and improve prompts with your team.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5">
    <div class="container">
        <div class="cta text-center shadow">
            <h3 class="fw-bold">Start Building Your Prompt Library</h3>
            <p class="mb-4">Join developers who optimize their workflow with DevGenius.</p>
            <a href="public/register.php" class="btn btn-light btn-lg px-4">Create Account</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <p class="mb-0">© 2026 DevGenius Solutions — All rights reserved</p>
</footer>

</body>
</html>