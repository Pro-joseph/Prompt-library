<?php
// Static demo version - no dynamic logic or database required
include("header.php");

$prompt = [
    'id' => 1,
    'title' => 'Refactor Python Loop',
    'content' => "Can you help me refactor this nested loop into a more efficient list comprehension in Python?\n\n```python\nresult = []\nfor i in range(10):\n    for j in range(5):\n        if i % 2 == 0:\n            result.append(i * j)\n```",
    'category_id' => 1
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: view-prompt.php?id=1");
    exit();
}
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
                <option value="1" selected>Code</option>
                <option value="2">SQL</option>
                <option value="3">DevOps</option>
                <option value="4">Marketing</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Prompt</button>
        <a href="prompts.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>