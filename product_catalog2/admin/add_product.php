<?php
require_once '../config.php';
require_once '../functions.php';

$cats    = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['product_name'] ?? '');
    $cat_id = (int)($_POST['category_id'] ?? 0);
    $price  = (float)($_POST['price'] ?? 0);
    $desc   = trim($_POST['description'] ?? '');
    $img    = trim($_POST['image_url'] ?? '');
    if ($img === '') $img = 'https://via.placeholder.com/300x200?text=Product';

    if ($name !== '' && $cat_id > 0 && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, description, price, image_url)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cat_id, $name, $desc, $price, $img]);
        $message = "Product added successfully!";
    } else {
        $error = "Please fill all required fields correctly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Product</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-form">
    <h2>Add New Product</h2>
    <?php if ($message): ?><div class="success-msg"><?= e($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name" required>

        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($cats as $cat): ?>
                <option value="<?= (int)$cat['category_id'] ?>"><?= e($cat['category_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" min="0.01" name="price" placeholder="Price (₹)" required>
        <textarea name="description" placeholder="Description" rows="3"></textarea>
        <input type="text" name="image_url" placeholder="Image URL (optional)">

        <button type="submit">Add Product</button>
    </form>

    <p style="margin-top:16px;"><a href="index.php" style="color:#0c831f;font-weight:600;">← Back to Admin</a></p>
</div>

</body>
</html>