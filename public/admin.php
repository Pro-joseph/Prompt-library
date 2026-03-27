<?php
session_start();
require_once "../database/db.php";
include ("header.php");

// Only admin allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


// ===== CATEGORIES =====
$categories = $conn->query("
    SELECT c.*, COUNT(p.id) as total_prompts
    FROM categories c
    LEFT JOIN prompts p ON p.category_id = c.id
    GROUP BY c.id
")->fetchAll(PDO::FETCH_ASSOC);

// ===== USERS =====
$users = $conn->query("
    SELECT u.*, COUNT(p.id) as total_prompts
    FROM users u
    LEFT JOIN prompts p ON p.user_id = u.id
    GROUP BY u.id
")->fetchAll(PDO::FETCH_ASSOC);

// ===== STATS =====
$total_prompts = $conn->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_categories = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// ===== TOP CONTRIBUTORS =====
$top_users = $conn->query("
    SELECT u.username, u.role, COUNT(p.id) as total
    FROM prompts p
    JOIN users u ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// ===== MOST USED PROMPTS =====
$top_prompts = $conn->query("
    SELECT p.title, c.name as category, p.usage_count
    FROM prompts p
    LEFT JOIN categories c ON c.id = p.category_id
    ORDER BY p.usage_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// create category
if (isset($_POST['create_category'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $color = $_POST['color'] ?? 'primary';
    $icon = $_POST['icon'] ?? 'tag';

    // validation
    if (empty($name)) {
        $error = "Category name is required";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO categories (name, description, color, icon)
            VALUES (:name, :description, :color, :icon)
        ");

        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':color' => $color,
            ':icon' => $icon
        ]);

        // redirect to prevent form resubmission
        header("Location: admin.php");
        exit();
    }
}
// Handle Edit Category
if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');
    $color = $_POST['color'] ?? 'primary';
    $icon = $_POST['icon'] ?? 'tag';

    $stmt = $conn->prepare("UPDATE categories SET name=:name, description=:description, color=:color, icon=:icon WHERE id=:id");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':color' => $color,
        ':icon' => $icon,
        ':id' => $id
    ]);
    header("Location: admin.php");
    exit();
}

// Delete category
if (isset($_GET['delete_cat'])) {
    $cat_id = (int)$_GET['delete_cat'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute([':id' => $cat_id]);
    header("Location: admin.php");
    exit();
}
//user tab
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $id = (int)$_POST['user_id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->execute([$username, $email, $role, $id]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // DELETE USER
    if (isset($_POST['delete_user'])) {
        $id = (int)$_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid py-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Administration</h1>
                    <p class="text-muted mb-0">Gérez les utilisateurs, catégories et paramètres</p>
                </div>
            </div>

            <!-- Admin Tabs -->
            <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button">
                        <i class="bi bi-tags me-2"></i>Catégories
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                        <i class="bi bi-people me-2"></i>Utilisateurs
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="adminTabsContent">
                <!-- Categories Tab -->
                <div class="tab-pane fade show active" id="categories" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 fw-bold">Gestion des catégories</h5>
                                        <form method="POST">
                                            <button type="submit" class="btn btn-primary btn-sm" data-bs-toggle="modal" name="add_cat" data-bs-target="#addCategoryModal">
                                                <i class="bi bi-plus-lg me-1"></i>Nouvelle catégorie
                                            </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="border-0 ps-4">Catégorie</th>
                                                    <th class="border-0">Description</th>
                                                    <th class="border-0 text-center">Prompts</th>
                                                    <th class="border-0 text-end pe-4">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($categories as $cat): ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-<?= htmlspecialchars($cat['color'] ?? 'primary') ?> me-2 p-2">
                                                                <i class="bi bi-<?= htmlspecialchars($cat['icon'] ?? 'tag') ?>"></i>
                                                            </span>
                                                            <span class="fw-medium"><?= htmlspecialchars($cat['name']) ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-muted"><?=  htmlspecialchars($cat['description'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark"><?= $cat['total_prompts'] ?></span>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                       <button class="btn btn-sm btn-outline-secondary me-1" 
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editCategoryModal"
                                                                data-id="<?= $cat['id'] ?>"
                                                                data-name="<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>"
                                                                data-description="<?= htmlspecialchars($cat['description'], ENT_QUOTES) ?>"
                                                                data-color="<?= $cat['color'] ?>"
                                                                data-icon="<?= $cat['icon'] ?>"
                                                                title="Modifier">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" title="Supprimer"
                                                            onclick="if(confirm('Supprimer cette catégorie ?')) { window.location='admin.php?delete_cat=<?= $cat['id'] ?>'; }">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold">Gestion des utilisateurs</h5>
                                
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 ps-4">Utilisateur</th>
                                            <th class="border-0">Email</th>
                                            <th class="border-0">Rôle</th>
                                            <th class="border-0 text-center">Prompts</th>
                                            <th class="border-0">Inscription</th>
                                            <th class="border-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3 bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle">
                                                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-medium"><?= htmlspecialchars($user['username']) ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted"><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?= htmlspecialchars($user['role']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark"><?= $user['total_prompts'] ?></span>
                                            </td>
                                            <td class="text-muted small">
                                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                                            </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>        
                        <div class="card-footer bg-white border-0 py-3">
                            <nav>
                                <ul class="pagination pagination-sm justify-content-center mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
    </main>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Nouvelle catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom de la catégorie</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Couleur</label>
                        <select name="color" class="form-select">
                            <option value="primary">Bleu</option>
                            <option value="success">Vert</option>
                            <option value="danger">Rouge</option>
                            <option value="warning">Jaune</option>
                            <option value="info">Cyan</option>
                            <option value="secondary">Gris</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Icône</label>
                        <select name="icon" class="form-select">
                            <option value="code-slash">Code</option>
                            <option value="database">Database</option>
                            <option value="gear">Gear</option>
                            <option value="megaphone">Megaphone</option>
                            <option value="file-text">Document</option>
                            <option value="bug">Bug</option>
                        </select>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>

                    <button type="submit" name="create_category" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Créer
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Modifier la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editCatId">
                <div class="mb-3">
                    <label class="form-label fw-medium">Nom de la catégorie</label>
                    <input type="text" name="name" id="editCatName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Description</label>
                    <textarea name="description" id="editCatDesc" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Couleur</label>
                    <select name="color" id="editCatColor" class="form-select">
                        <option value="primary">Bleu</option>
                        <option value="success">Vert</option>
                        <option value="danger">Rouge</option>
                        <option value="warning">Jaune</option>
                        <option value="info">Cyan</option>
                        <option value="secondary">Gris</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Icône</label>
                    <select name="icon" id="editCatIcon" class="form-select">
                        <option value="code-slash">Code</option>
                        <option value="database">Database</option>
                        <option value="gear">Gear</option>
                        <option value="megaphone">Megaphone</option>
                        <option value="file-text">Document</option>
                        <option value="bug">Bug</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" name="edit_category" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </button>
            </div>
        </form>
    </div>
</div>
<?php foreach ($users as $user): ?>
<!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Modifier utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label>Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Rôle</label>
                        <select name="role" class="form-select" required>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="developer" <?= $user['role'] === 'developer' ? 'selected' : '' ?>>Developer</option>
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="update_user" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
                </form>
            </div>
            </div>
            </div>

<!-- Delete User Modal -->
        <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Supprimer utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer <strong><?= htmlspecialchars($user['username']) ?></strong> ?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" name="delete_user" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
            </form>
        </div>
        </div>
<?php endforeach; ?>
<!-- JS to populate Edit Modal -->
<script>
var editModal = document.getElementById('editCategoryModal');
editModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('editCatId').value = button.getAttribute('data-id');
    document.getElementById('editCatName').value = button.getAttribute('data-name');
    document.getElementById('editCatDesc').value = button.getAttribute('data-description');
    document.getElementById('editCatColor').value = button.getAttribute('data-color');
    document.getElementById('editCatIcon').value = button.getAttribute('data-icon');
});
</script>
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
