<?php
require_once 'config.php';
require_once 'functions.php';

$cat_id = (int)($_GET['id'] ?? 0);
$sort   = $_GET['sort'] ?? 'newest';

$stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->execute([$cat_id]);
$category = $stmt->fetch();

if (!$category) {
    http_response_code(404);
    die("Category not found. <a href='index.php'>Go home</a>");
}

$stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p
                       JOIN categories c ON p.category_id = c.category_id
                       WHERE p.category_id = ?
                       ORDER BY " . sort_sql($sort));
$stmt->execute([$cat_id]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($category['category_name']) ?> - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<h2 class="section-title"><?= e($category['category_name']) ?>
    <span style="font-size:13px;color:#888;font-weight:400;">(<?= count($products) ?> items)</span>
</h2>
<?php if (!empty($category['description'])): ?>
    <p style="padding:0 20px;color:#777;font-size:14px;"><?= e($category['description']) ?></p>
<?php endif; ?>

<form class="sort-bar" method="GET" action="category.php">
    <input type="hidden" name="id" value="<?= (int)$cat_id ?>">
    <label>Sort by:
        <select name="sort" onchange="this.form.submit()">
            <?= sort_select($sort) ?>
        </select>
    </label>
</form>

<?php render_product_grid($products, $_SERVER['REQUEST_URI']); ?>

<a href="index.php" class="back-link">← Back to Home</a>

<?php include 'footer.php'; ?>