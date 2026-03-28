<?php
// Static demo version - no dynamic logic or database required
include ("header.php");

$user = [
    'username' => 'Demo User',
    'email' => 'demo@example.com',
    'role' => 'developer',
    'created_at' => '2026-03-27 10:00:00'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil - <?= htmlspecialchars($user['username']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                    <h2 class="mt-2"><?= $user['username'] ?></h2>
                    <p class="text-muted"><?= ucfirst($user['role']) ?></p>
                </div>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
                    <li class="list-group-item"><strong>Rôle:</strong> <?= htmlspecialchars($user['role']) ?></li>
                    <li class="list-group-item"><strong>Inscrit le:</strong> <?= date("d/m/Y H:i", strtotime($user['created_at'])) ?></li>
                </ul>

                <div class="d-grid gap-2">
                    <a href="change_password.php" class="btn btn-outline-primary">Changer le mot de passe</a>
                    <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>