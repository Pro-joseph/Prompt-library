<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header("Location: prompts.php");
    exit();
}

// Fetch the prompt (only owner or admin can edit)
$stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM prompts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch();

if (!$prompt) {
    header("Location: prompts.php");
    exit();
}

// Only owner or admin can edit
if ($prompt['user_id'] != $user_id && $_SESSION['role'] !== 'admin') {
    header("Location: view-prompt.php?id=$id");
    exit();
}

// Handle delete
if (isset($_GET['delete']) && $_GET['delete'] == 1) {
    $stmt = $conn->prepare("DELETE FROM prompts WHERE id = ? AND (user_id = ? OR ? = 'admin')");
    $stmt->execute([$id, $user_id, $_SESSION['role']]);
    header("Location: prompts.php");
    exit();
}

$error = '';

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $content     = trim($_POST['content'] ?? '');
    $tags        = trim($_POST['tags'] ?? '');

    if (empty($title) || empty($content) || $category_id === 0) {
        $error = "Le titre, la catégorie et le contenu sont obligatoires.";
    } else {
        $stmt = $conn->prepare("UPDATE prompts SET title=?, category_id=?, description=?, content=?, tags=? WHERE id=?");
        $stmt->execute([$title, $category_id, $description, $content, $tags, $id]);
        header("Location: view-prompt.php?id=$id");
        exit();
    }
}

// Load categories
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

include("header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Prompt - <?= htmlspecialchars($prompt['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="prompts.php" class="text-decoration-none">Prompts</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">Modifier le prompt</h1>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg"
                                       value="<?= htmlspecialchars($prompt['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Catégorie <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select form-select-lg" required>
                                    <option value="" disabled>Sélectionnez une catégorie</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $prompt['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($prompt['description'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Contenu <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control font-monospace" rows="10" required><?= htmlspecialchars($prompt['content']) ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Tags</label>
                                <input type="text" name="tags" class="form-control"
                                       value="<?= htmlspecialchars($prompt['tags'] ?? '') ?>"
                                       placeholder="api, rest, python (séparés par des virgules)">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="view-prompt.php?id=<?= $id ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Annuler
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="edit-prompt.php?id=<?= $id ?>&delete=1"
                                       class="btn btn-outline-danger"
                                       onclick="return confirm('Supprimer ce prompt définitivement ?')">
                                        <i class="bi bi-trash me-2"></i>Supprimer
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </form>
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