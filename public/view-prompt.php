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

// Fetch the prompt
$stmt = $conn->prepare("
    SELECT p.*, c.name as category, u.username as author
    FROM prompts p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN users u ON u.id = p.user_id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$prompt = $stmt->fetch();

if (!$prompt) {
    header("Location: prompts.php");
    exit();
}

// Related prompts (same category, exclude current)
$stmt2 = $conn->prepare("
    SELECT p.id, p.title, p.description, c.name as category
    FROM prompts p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.category_id = ? AND p.id != ?
    LIMIT 4
");
$stmt2->execute([$prompt['category_id'], $id]);
$related_prompts = $stmt2->fetchAll();

// Top contributors
$top_contributors = $conn->query("
    SELECT u.username, u.role, COUNT(p.id) as total_prompts
    FROM prompts p
    JOIN users u ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total_prompts DESC
    LIMIT 5
")->fetchAll();

include("header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prompt['title']) ?> - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<main class="main-content">
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="prompts.php" class="text-decoration-none">Prompts</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($prompt['title']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <!-- Prompt Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary-subtle text-primary mb-2"><?= htmlspecialchars($prompt['category'] ?? '') ?></span>
                            <h1 class="h3 fw-bold mb-1"><?= htmlspecialchars($prompt['title']) ?></h1>
                            <p class="text-muted mb-0"><?= htmlspecialchars($prompt['description'] ?? '') ?></p>
                            <small class="text-muted">Par <?= htmlspecialchars($prompt['author']) ?></small>
                        </div>
                        <?php if ($prompt['user_id'] == $user_id || $_SESSION['role'] === 'admin'): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="edit-prompt.php?id=<?= $id ?>"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                       href="edit-prompt.php?id=<?= $id ?>&delete=1"
                                       onclick="return confirm('Supprimer ce prompt définitivement ?')">
                                        <i class="bi bi-trash me-2"></i>Supprimer
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <!-- Prompt Content -->
                        <div class="prompt-content-box bg-dark text-light rounded-3 p-4 mb-4 position-relative">
                            <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3" id="copyPrompt">
                                <i class="bi bi-clipboard"></i> Copier
                            </button>
                            <pre class="mb-0 text-light" id="promptContent"><code><?= htmlspecialchars($prompt['content']) ?></code></pre>
                        </div>

                        <!-- Tags -->
                        <?php if (!empty($prompt['tags'])): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Tags</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach (explode(',', $prompt['tags']) as $tag): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" id="copyWithVars">
                                <i class="bi bi-clipboard-check me-2"></i>Copier avec variables
                            </button>
                            <?php if ($prompt['user_id'] == $user_id || $_SESSION['role'] === 'admin'): ?>
                            <a href="edit-prompt.php?id=<?= $id ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Related Prompts -->
                <?php if (!empty($related_prompts)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Prompts similaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($related_prompts as $rp): ?>
                            <div class="col-md-6">
                                <a href="view-prompt.php?id=<?= $rp['id'] ?>" class="text-decoration-none">
                                    <div class="card border h-100 prompt-card-mini">
                                        <div class="card-body">
                                            <span class="badge bg-primary-subtle text-primary small mb-2"><?= htmlspecialchars($rp['category']) ?></span>
                                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($rp['title']) ?></h6>
                                            <p class="text-muted small mb-0"><?= htmlspecialchars($rp['description'] ?? '') ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar: Top Contributors -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Top Contributeurs</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($top_contributors as $contrib): ?>
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                        <?= strtoupper(substr($contrib['username'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium"><?= htmlspecialchars($contrib['username']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($contrib['role']) ?></small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?= $contrib['total_prompts'] ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
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
<script>
document.getElementById('copyPrompt').addEventListener('click', function() {
    const content = document.getElementById('promptContent').textContent;
    navigator.clipboard.writeText(content).then(() => {
        this.innerHTML = '<i class="bi bi-check"></i> Copié!';
        setTimeout(() => {
            this.innerHTML = '<i class="bi bi-clipboard"></i> Copier';
        }, 2000);
    });
});

document.getElementById('copyWithVars').addEventListener('click', function() {
    const content = document.getElementById('promptContent').textContent;
    navigator.clipboard.writeText(content).then(() => {
        this.innerHTML = '<i class="bi bi-check me-2"></i>Copié!';
        setTimeout(() => {
            this.innerHTML = '<i class="bi bi-clipboard-check me-2"></i>Copier avec variables';
        }, 2000);
    });
});
</script>
</body>
</html>