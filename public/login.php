<?php
session_start();
require_once '../database/db.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['usernameOrEmail'] ?? '');
    $password        = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :uoe OR email = :uoe2 LIMIT 1");
        $stmt->execute([':uoe' => $usernameOrEmail, ':uoe2' => $usernameOrEmail]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Identifiants invalides. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Prompt Repository</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body class="auth-page">

<div class="container">
  <div class="row min-vh-100 align-items-center justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 p-4">
        <div class="text-center mb-4">
          <i class="bi bi-braces-asterisk fs-1 text-primary"></i>
          <h1 class="h4 fw-bold mt-2">Connexion</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
          <div class="mb-3">
            <label class="form-label">Nom d'utilisateur ou Email</label>
            <input type="text" name="usernameOrEmail" class="form-control"
                   value="<?= htmlspecialchars($_POST['usernameOrEmail'] ?? '') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <div class="input-group">
              <input type="password" name="password" id="password" class="form-control" required>
              <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <p class="text-center mt-3 small">
          Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </p>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    if(password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>

</body>
</html>