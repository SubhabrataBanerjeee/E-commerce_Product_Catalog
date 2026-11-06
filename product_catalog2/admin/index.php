<?php
require_once '../config.php';
require_once '../functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    $img  = trim($_POST['image_url']);
    if ($img === '') $img = 'https://via.placeholder.com/300x200?text=Category';

    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description, image_url) VALUES (?, ?, ?)");
        $stmt->execute([$name, $desc, $img]);
        $message = "Category added successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Category</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-form">
    <h2>⚙️ Admin Panel</h2>
    <?php if ($message): ?><div class="success-msg"><?= e($message) ?></div><?php endif; ?>

    <h3>Add New Category</h3>
    <form method="POST">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <textarea name="description" placeholder="Description (optional)" rows="2"></textarea>
        <input type="text" name="image_url" placeholder="Category Image URL (optional)">
        <button type="submit" name="add_category">Add Category</button>
    </form>

    <p style="margin-top:16px;"><a href="add_product.php" style="color:#0c831f;font-weight:600;">➕ Add a Product instead</a></p>
    <p style="margin-top:8px;"><a href="../index.php" style="color:#0c831f;font-weight:600;">← Back to Store</a></p>
</div>

</body>
</html>