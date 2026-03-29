<?php
session_start();
require_once '../database/db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ===== STATS =====
$total_prompts     = $conn->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$my_prompts_count  = $conn->prepare("SELECT COUNT(*) FROM prompts WHERE user_id = ?");
$my_prompts_count->execute([$user_id]);
$my_prompts_count  = $my_prompts_count->fetchColumn();

$total_categories  = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_contributors = $conn->query("SELECT COUNT(DISTINCT user_id) FROM prompts")->fetchColumn();

// ===== MY RECENT PROMPTS =====
$stmt = $conn->prepare("
    SELECT p.id, p.title, c.name as category, u.username as author, p.created_at
    FROM prompts p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN users u ON u.id = p.user_id
    WHERE p.user_id = :uid
    ORDER BY p.created_at DESC
    LIMIT 5
");
$stmt->execute([':uid' => $user_id]);
$my_prompts = $stmt->fetchAll();

// ===== CATEGORIES =====
$categories = $conn->query("
    SELECT c.id, c.name, c.color, c.icon, COUNT(p.id) as total
    FROM categories c
    LEFT JOIN prompts p ON p.category_id = c.id
    GROUP BY c.id
")->fetchAll();

// ===== TOP CONTRIBUTORS =====
$top_contributors = $conn->query("
    SELECT u.username, u.role, COUNT(p.id) as total_prompts
    FROM prompts p
    JOIN users u ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total_prompts DESC
    LIMIT 5
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php include('header.php'); ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-banner p-4 rounded-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h3 fw-bold text-white mb-2">Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
                                <p class="text-white-50 mb-0">Gérez et partagez vos prompts performants avec votre équipe.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="add-prompt.php" class="btn btn-light">
                                    <i class="bi bi-plus-lg me-2"></i>Nouveau Prompt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-3 me-3">
                                    <i class="bi bi-file-text fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Total Prompts</p>
                                    <h3 class="fw-bold mb-0"><?= $total_prompts ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success-subtle text-success rounded-3 p-3 me-3">
                                    <i class="bi bi-person-plus fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Mes Prompts</p>
                                    <h3 class="fw-bold mb-0"><?= $my_prompts_count ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-3 me-3">
                                    <i class="bi bi-tags fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Catégories</p>
                                    <h3 class="fw-bold mb-0"><?= $total_categories ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info-subtle text-info rounded-3 p-3 me-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0">Contributeurs</p>
                                    <h3 class="fw-bold mb-0"><?= $total_contributors ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent Prompts -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold">My Prompts</h5>
                                <a href="prompts.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 ps-4">Titre</th>
                                            <th class="border-0">Catégorie</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($my_prompts)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Aucun prompt. <a href="add-prompt.php">Créez votre premier prompt !</a>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                        <?php foreach ($my_prompts as $prompt): ?>
                                        <tr>
                                            <td class="ps-4"><?= htmlspecialchars($prompt['title']) ?></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($prompt['category'] ?? 'N/A') ?></span></td>
                                            <td><?= date('d M Y', strtotime($prompt['created_at'])) ?></td>
                                            <td class="text-center">
                                                <a href="view-prompt.php?id=<?= $prompt['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-prompt.php?id=<?= $prompt['id'] ?>" class="btn btn-sm btn-outline-success" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Categories -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold">Catégories</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($categories as $cat): ?>
                                <a href="prompts.php?search=&category=<?= $cat['id'] ?>&author=" 
                                   class="btn btn-outline-<?= htmlspecialchars($cat['color'] ?? 'primary') ?> btn-sm">
                                    <i class="bi bi-<?= htmlspecialchars($cat['icon'] ?? 'tag') ?> me-1"></i>
                                    <?= htmlspecialchars($cat['name']) ?>
                                    <span class="badge bg-<?= htmlspecialchars($cat['color'] ?? 'primary') ?> ms-1"><?= $cat['total'] ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Top Contributors -->
                    <div class="card border-0 shadow-sm">
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
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted small mb-0">&copy; 2026 DevGenius Solutions. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="#" class="text-muted small text-decoration-none me-3">Aide</a>
                    <a href="#" class="text-muted small text-decoration-none me-3">Confidentialité</a>
                    <a href="#" class="text-muted small text-decoration-none">Conditions</a>
                </div>
            </div>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
