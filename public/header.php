<?php
// header.php - shared navigation component
// IMPORTANT: Call session_start() in the parent page BEFORE including this file.
$current_user = $_SESSION['username'] ?? 'Guest';
$initials     = strtoupper(substr($current_user, 0, 2));
$is_admin     = ($_SESSION['role'] ?? '') === 'admin';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-braces-asterisk me-2 text-primary"></i>
            <span class="fw-bold">Prompt Repository</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'prompts.php' ? 'active' : '' ?>" href="prompts.php">
                        <i class="bi bi-collection me-1"></i>All Prompts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'add-prompt.php' ? 'active' : '' ?>" href="add-prompt.php">
                        <i class="bi bi-plus-circle me-1"></i>Nouveau Prompt
                    </a>
                </li>
                <?php if ($is_admin): ?>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'admin.php' ? 'active' : '' ?>" href="admin.php">
                        <i class="bi bi-shield-check me-1"></i>Administration
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm"><?= htmlspecialchars($initials) ?></div>
                        <span><?= htmlspecialchars($current_user) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Mon Profil</a></li>
                        <li><a class="dropdown-item" href="change_password.php"><i class="bi bi-key me-2"></i>Changer le mot de passe</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>