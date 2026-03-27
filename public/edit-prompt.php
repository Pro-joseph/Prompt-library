<?php
session_start();
require_once "../database/db.php"; // your database connection
include("header.php"); // your header

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get prompt ID from GET
$prompt_id = $_GET['id'] ?? null;
if (!$prompt_id) {
    header("Location: index.php");
    exit();
}

// Fetch prompt from DB
$stmt = $conn->prepare("SELECT * FROM prompts WHERE id = :id");
$stmt->execute([':id' => $prompt_id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prompt) {
    echo "<p>Prompt not found.</p>";
    exit();
}

// Check if current user is owner or admin
if ($prompt['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
    echo "<p>You don't have permission to edit this prompt.</p>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category_id'];

    // Basic validation
    if (empty($title) || empty($content) || !$category_id) {
        $error = "All fields are required.";
    } else {
        $update = $conn->prepare("
            UPDATE prompts 
            SET title = :title, content = :content, category_id = :category_id
            WHERE id = :id
        ");
        $update->execute([
            ':title' => $title,
            ':content' => $content,
            ':category_id' => $category_id,
            ':id' => $prompt_id
        ]);
        header("Location: view-prompt.php?id=". $prompt['id']);
        exit();
    }
}

// Fetch categories for dropdown
$categories_stmt = $conn->query("SELECT * FROM categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Edit Prompt</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($prompt['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($prompt['content']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $prompt['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Prompt</button>
        <a href="prompts.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>