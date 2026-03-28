<?php
// Static demo version - no dynamic logic or database required
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "Mot de passe mis à jour avec succès (Simulation).";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Changer le mot de passe</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-key fs-1 text-primary"></i>
                    <h2 class="mt-2">Changer le mot de passe</h2>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="currentPassword" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="newPassword" class="form-control" required minlength="6">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="confirmPassword" class="form-control" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                </form>

                <p class="text-center mt-3 small">
                    <a href="profile.php">Retour au profil</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>