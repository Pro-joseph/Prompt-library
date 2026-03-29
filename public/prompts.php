<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Search/filter params
$search   = trim($_GET['search'] ?? '');
$category = (int)($_GET['category'] ?? 0);
$author   = $_GET['author'] ?? '';

// Build query
$where  = ["1=1"];
$params = [];

if (!empty($search)) {
    $where[]  = "(p.title LIKE ? OR p.content LIKE ? OR p.tags LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category > 0) {
    $where[]  = "p.category_id = ?";
    $params[] = $category;
}

if ($author === 'me') {
    $where[]  = "p.user_id = ?";
    $params[] = $user_id;
}

$whereStr = implode(' AND ', $where);

$stmt = $conn->prepare("
    SELECT p.id, p.title, p.description, p.content, p.tags, p.created_at,
           c.name as category, c.color,
           u.username as author
    FROM prompts p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN users u ON u.id = p.user_id
    WHERE $whereStr
    ORDER BY p.created_at DESC
");
$stmt->execute($params);
$prompts = $stmt->fetchAll();

// Categories for filter
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

include("header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tous les Prompts - Prompt Repository</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<main class="main-content">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Tous les Prompts</h1>
            <p class="text-muted mb-0"><?= count($prompts) ?> prompt(s) trouvé(s)</p>
        </div>
        <a href="add-prompt.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Nouveau Prompt
        </a>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-medium">Rechercher</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                           class="form-control" placeholder="Titre, contenu, tags...">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-medium">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-medium">Auteur</label>
                    <select name="author" class="form-select">
                        <option value="">Tous</option>
                        <option value="me" <?= ($author === 'me') ? 'selected' : '' ?>>Mes prompts</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filtrer
                        </button>
                        <a href="prompts.php" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- PROMPTS GRID -->
    <?php if (empty($prompts)): ?>
    <div class="text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted"></i>
        <h5 class="mt-3 text-muted">Aucun prompt trouvé</h5>
        <p class="text-muted">Essayez de modifier votre recherche ou <a href="add-prompt.php">créez un nouveau prompt</a>.</p>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($prompts as $prompt): ?>
        <div class="col-md-6 col-lg-4">
            <a href="view-prompt.php?id=<?= $prompt['id'] ?>" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm border-0 prompt-card">
                    <div class="card-body">
                        <span class="badge bg-<?= htmlspecialchars($prompt['color'] ?? 'secondary') ?> mb-2">
                            <?= htmlspecialchars($prompt['category'] ?? 'N/A') ?>
                        </span>
                        <h5 class="fw-bold mb-2"><?= htmlspecialchars($prompt['title']) ?></h5>
                        <p class="text-muted small mb-2"><?= htmlspecialchars(mb_strimwidth($prompt['description'] ?? '', 0, 100, '...')) ?></p>
                        <code class="small text-muted"><?= htmlspecialchars(mb_strimwidth($prompt['content'], 0, 80, '...')) ?></code>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($prompt['author']) ?></small>
                        <small class="text-muted"><?= date('d M Y', strtotime($prompt['created_at'])) ?></small>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

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