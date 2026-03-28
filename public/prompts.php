<?php
// Static demo version - no dynamic logic or database required
include ("header.php");

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$author = $_GET['author'] ?? '';
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
            <option value="1">Code</option>
            <option value="2">Marketing</option>
            <option value="3">DevOps</option>
            <option value="4">SQL</option>
            <option value="5">Testing</option>
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

<div class="col-md-6 col-lg-4">
    <a href="view-prompt.php?id=1" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="badge bg-secondary mb-2">Code</span>
                <h5>Refactor Python Loop</h5>
                <p class="text-muted small">A prompt to refactor nested loops into list comprehensions.</p>
                <code class="small">Can you help me refactor this nested loop into a more efficient...</code>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <small>Demo User</small>
                <small>27 Mar 2026</small>
            </div>
        </div>
    </a>
</div>
<div class="col-md-6 col-lg-4">
    <a href="view-prompt.php?id=2" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="badge bg-secondary mb-2">SQL</span>
                <h5>Optimize JOIN Query</h5>
                <p class="text-muted small">Improve performance of complex SQL joins.</p>
                <code class="small">Write a optimized SQL query that joins the users table with...</code>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <small>Alex Dev</small>
                <small>25 Mar 2026</small>
            </div>
        </div>
    </a>
</div>
<div class="col-md-6 col-lg-4">
    <a href="view-prompt.php?id=3" class="text-decoration-none text-dark">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="badge bg-secondary mb-2">Marketing</span>
                <h5>Email Campaign Copy</h5>
                <p class="text-muted small">Professional email copy for product launches.</p>
                <code class="small">Generate a catchy subject line and body for a new feature...</code>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <small>Sarah M</small>
                <small>22 Mar 2026</small>
            </div>
        </div>
    </a>
</div>

</div>



<!-- PAGINATION -->
<nav class="mt-4">
<li class="page-item active"><a class="page-link" href="#">1</a></li>
<li class="page-item"><a class="page-link" href="#">2</a></li>
</nav>

</div>

</body>
</html>