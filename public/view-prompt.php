<?php
session_start();
include("../database/db.php");
include("header.php");

// Fetch prompt info  prompt by id
$prompt_id = $_GET['id'] ?? null;

if (!$prompt_id) {
    die("Invalid prompt ID");
}

$stmt = $conn->prepare("
    SELECT prompts.*, users.username AS author, categories.name AS category
    FROM prompts
    JOIN users ON prompts.user_id = users.id
    JOIN categories ON prompts.category_id = categories.id
    WHERE prompts.id = ?
");
$stmt->execute([$prompt_id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prompt) {
    die("Prompt not found");
}

// Fetch related prompts (same category)
$stmt = $conn->prepare("
    SELECT prompts.*, categories.name AS category
    FROM prompts
    JOIN categories ON prompts.category_id = categories.id
    WHERE prompts.category_id = ? 
    AND prompts.id != ?
    LIMIT 4
");

$stmt->execute([$prompt['category_id'], $prompt_id]);
$related_prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch top contributors
$stmt = $conn->query("
    SELECT users.username, COUNT(prompts.id) AS total_prompts
    FROM prompts
    JOIN users ON prompts.user_id = users.id
    GROUP BY users.id
    ORDER BY total_prompts DESC
    LIMIT 5
");
$top_contributors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prompt['title'] ?? 'Prompt') ?> - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<!-- Navbar here (same as your template) -->

<main class="main-content">
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="prompts.php" class="text-decoration-none">Prompts</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($prompt['title'] ?? 'Prompt') ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <!-- Prompt Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary-subtle text-primary mb-2"><?= htmlspecialchars($prompt['category'] ?? '') ?></span>
                            <h1 class="h3 fw-bold mb-1"><?= htmlspecialchars($prompt['title'] ?? '') ?></h1>
                            <p class="text-muted mb-0"><?= htmlspecialchars($prompt['description'] ?? '') ?></p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $prompt['user_id']): ?>
                                <li><a class="dropdown-item" href="edit-prompt.php?id=<?= $prompt['id'] ?>"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-share me-2"></i>Partager</a></li>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $prompt['user_id']): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="delete-prompt.php?id=<?= $prompt['id'] ?>"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Prompt Content -->
                        <div class="prompt-content-box bg-dark text-light rounded-3 p-4 mb-4 position-relative">
                            <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3" id="copyPrompt">
                                <i class="bi bi-clipboard"></i> Copier
                            </button>
                            <pre class="mb-0 text-light" id="promptContent"><code><?= htmlspecialchars($prompt['content'] ?? '') ?></code></pre>
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Tags</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php
                                $tags = explode(',', $prompt['tags'] ?? '');
                                foreach ($tags as $tag):
                                    if(trim($tag)):
                                ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary" id="copyWithVars"><i class="bi bi-clipboard-check me-2"></i>Copier avec variables</button>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $prompt['user_id']): ?>
                            <a href="edit-prompt.php?id=<?= $prompt['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil me-2"></i>Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Related Prompts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Prompts similaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach($related_prompts as $rp): ?>
                            <div class="col-md-6">
                                <a href="view-prompt.php?id=<?= $rp['id'] ?>" class="text-decoration-none">
                                    <div class="card border h-100 prompt-card-mini">
                                        <div class="card-body">
                                            <span class="badge bg-primary-subtle text-primary small mb-2"><?= htmlspecialchars($rp['category']) ?></span>
                                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($rp['title']) ?></h6>
                                            <p class="text-muted small mb-0"><?= htmlspecialchars($rp['description']) ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Top Contributors -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Top Contributeurs</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($top_contributors as $c): ?>
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-warning"><?= strtoupper(substr($c['username'],0,2)) ?></div>
                                    <div>
                                        <p class="mb-0 fw-medium"><?= htmlspecialchars($c['username']) ?></p>
                                        <small class="text-muted">Développeur</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning rounded-pill"><?= $c['total_prompts'] ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer and Scripts (same as your template) -->
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
</script>
</body>
</html>