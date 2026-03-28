<?php
// Static demo version - no dynamic logic or database required
include("header.php");

$prompt = [
    'id' => 1,
    'title' => 'Refactor Python Loop',
    'description' => 'A prompt to refactor nested loops into list comprehensions.',
    'content' => "Can you help me refactor this nested loop into a more efficient list comprehension in Python?\n\n```python\nresult = []\nfor i in range(10):\n    for j in range(5):\n        if i % 2 == 0:\n            result.append(i * j)\n```",
    'category' => 'Code',
    'tags' => 'python,optimization,clean-code',
    'user_id' => 1,
    'author' => 'Demo User'
];

$related_prompts = [
    ['id' => 2, 'title' => 'Optimize JOIN Query', 'description' => 'Improve performance of complex SQL joins.', 'category' => 'SQL'],
    ['id' => 3, 'title' => 'Email Campaign Copy', 'description' => 'Professional email copy for product launches.', 'category' => 'Marketing']
];

$top_contributors = [
    ['username' => 'Demo User', 'total_prompts' => 12],
    ['username' => 'Alex Dev', 'total_prompts' => 45]
];
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
                                <li><a class="dropdown-item" href="edit-prompt.php?id=1"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-share me-2"></i>Partager</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
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
                                    <span class="badge bg-secondary">python</span>
                                    <span class="badge bg-secondary">optimization</span>
                                    <span class="badge bg-secondary">clean-code</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                            <button class="btn btn-primary" id="copyWithVars"><i class="bi bi-clipboard-check me-2"></i>Copier avec variables</button>
                            <a href="edit-prompt.php?id=1" class="btn btn-outline-secondary"><i class="bi bi-pencil me-2"></i>Modifier</a>
                    </div>
                </div>

                <!-- Related Prompts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Prompts similaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="view-prompt.php?id=2" class="text-decoration-none">
                                    <div class="card border h-100 prompt-card-mini">
                                        <div class="card-body">
                                            <span class="badge bg-primary-subtle text-primary small mb-2">SQL</span>
                                            <h6 class="fw-bold text-dark mb-1">Optimize JOIN Query</h6>
                                            <p class="text-muted small mb-0">Improve performance of complex SQL joins.</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="view-prompt.php?id=3" class="text-decoration-none">
                                    <div class="card border h-100 prompt-card-mini">
                                        <div class="card-body">
                                            <span class="badge bg-primary-subtle text-primary small mb-2">Marketing</span>
                                            <h6 class="fw-bold text-dark mb-1">Email Campaign Copy</h6>
                                            <p class="text-muted small mb-0">Professional email copy for product launches.</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
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
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-warning">DU</div>
                                    <div>
                                        <p class="mb-0 fw-medium">Demo User</p>
                                        <small class="text-muted">Développeur</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning rounded-pill">12</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary">AD</div>
                                    <div>
                                        <p class="mb-0 fw-medium">Alex Dev</p>
                                        <small class="text-muted">Admin</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning rounded-pill">45</span>
                            </li>
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