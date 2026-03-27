<?php
session_start();
require_once __DIR__ . '/../database/db.php';
include ("header.php");

// ====== INPUTS ======
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$author = $_GET['author'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 6;
$offset = ($page - 1) * $limit;

// ====== BASE QUERY ======
$sql = "
SELECT prompts.*, categories.name AS category_name, users.username AS author
FROM prompts
JOIN categories ON prompts.category_id = categories.id
JOIN users ON prompts.user_id = users.id
WHERE 1=1
";

$params = [];

// ====== FILTERS ======
if (!empty($search)) {
    $sql .= " AND prompts.title LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $sql .= " AND categories.id = ?";
    $params[] = $category;
}

if ($author === 'me') {
    $sql .= " AND prompts.user_id = ?";
    $params[] = $_SESSION['user_id'];
}

// ====== ORDER + PAGINATION ======
$sql .= " ORDER BY prompts.created_at DESC LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll();

// ====== COUNT FOR PAGINATION ======
$countSql = "
SELECT COUNT(*) 
FROM prompts
JOIN categories ON prompts.category_id = categories.id
WHERE 1=1
";

$countParams = [];

if (!empty($search)) {
    $countSql .= " AND prompts.title LIKE ?";
    $countParams[] = "%$search%";
}

if (!empty($category)) {
    $countSql .= " AND categories.id = ?";
    $countParams[] = $category;
}

if ($author === 'me') {
    $countSql .= " AND prompts.user_id = ?";
    $countParams[] = $_SESSION['user_id'];
}

$stmt = $conn->prepare($countSql);
$stmt->execute($countParams);
$total = $stmt->fetchColumn();

$totalPages = ceil($total / $limit);

// ====== FETCH CATEGORIES ======
$stmt = $conn->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Prompts</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">

<h2 class="mb-4">Prompts</h2>

<!-- FILTER FORM -->
<form method="GET" class="row g-3 mb-4">

    <div class="col-md-4">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
        class="form-control" placeholder="Search...">
    </div>

    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" 
                <?= ($category == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="author" class="form-select">
            <option value="">All</option>
            <option value="me" <?= ($author === 'me') ? 'selected' : '' ?>>My prompts</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

</form>

<!-- PROMPTS GRID -->
<div class="row g-4">

<?php if (empty($prompts)): ?>
    <p>No prompts found</p>
<?php endif; ?>

<?php foreach ($prompts as $p): ?>
<div class="col-md-6 col-lg-4">
    <a href="view-prompt.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm">

            <div class="card-body">
                <span class="badge bg-secondary mb-2">
                    <?= htmlspecialchars($p['category_name']) ?>
                </span>

                <h5><?= htmlspecialchars($p['title']) ?></h5>

                <p class="text-muted small">
                    <?= htmlspecialchars($p['description']) ?>
                </p>

                <code class="small">
                    <?= htmlspecialchars(substr($p['content'], 0, 80)) ?>...
                </code>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <small><?= htmlspecialchars($p['author']) ?></small>
                <small><?= date('d M Y', strtotime($p['created_at'])) ?></small>
            </div>

        </div>
    </a>
</div>
<?php endforeach; ?>

</div>



<!-- PAGINATION -->
<nav class="mt-4">
<ul class="pagination">

<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
    <a class="page-link" 
    href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= $category ?>&author=<?= $author ?>">
        <?= $i ?>
    </a>
</li>
<?php endfor; ?>

</ul>
</nav>

</div>

</body>
</html>