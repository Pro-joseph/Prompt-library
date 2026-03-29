<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch real user from DB
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Count their prompts
$stmt2 = $conn->prepare("SELECT COUNT(*) FROM prompts WHERE user_id = ?");
$stmt2->execute([$user_id]);
$prompt_count = $stmt2->fetchColumn();

include("header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil - <?= htmlspecialchars($user['username']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<main class="main-content">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card shadow-sm border-0 p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-2 fw-bold"
                         style="width:80px;height:80px;">
                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                    </div>
                    <h2 class="fw-bold mt-2"><?= htmlspecialchars($user['username']) ?></h2>
                    <p class="text-muted"><?= ucfirst(htmlspecialchars($user['role'])) ?></p>
                </div>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="fw-medium"><i class="bi bi-envelope me-2 text-primary"></i>Email</span>
                        <span class="text-muted"><?= htmlspecialchars($user['email']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="fw-medium"><i class="bi bi-person-badge me-2 text-primary"></i>Rôle</span>
                        <span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($user['role']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="fw-medium"><i class="bi bi-file-text me-2 text-primary"></i>Prompts créés</span>
                        <span class="badge bg-success rounded-pill"><?= $prompt_count ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="fw-medium"><i class="bi bi-calendar me-2 text-primary"></i>Inscrit le</span>
                        <span class="text-muted"><?= date("d/m/Y", strtotime($user['created_at'])) ?></span>
                    </li>
                </ul>

                <div class="d-grid gap-2">
                    <a href="change_password.php" class="btn btn-outline-primary">
                        <i class="bi bi-key me-2"></i>Changer le mot de passe
                    </a>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
</main>

<!-- Footer -->
<footer class="footer bg-light border-top py-3 mt-auto">
    <div class="container-fluid">
        <p class="text-muted small mb-0 text-center">&copy; 2026 DevGenius Solutions. Tous droits réservés.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>