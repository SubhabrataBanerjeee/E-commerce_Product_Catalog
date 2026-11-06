<?php
require_once 'config.php';
require_once 'functions.php';

$sort       = $_GET['sort'] ?? 'newest';
$cat_filter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

$sql    = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id";
$params = [];
if ($cat_filter > 0) {
    $sql .= " WHERE p.category_id = ?";
    $params[] = $cat_filter;
}
$sql .= " ORDER BY " . sort_sql($sort) . " LIMIT 24";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickCart - Product Catalog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="hero">
    <h1>Groceries in minutes ⚡</h1>
    <p>Fresh produce, snacks and daily essentials — delivered fast.</p>
</div>

<!-- ===== Shop by Category (with pictures) ===== -->
<h2 class="section-title">Shop by Category</h2>
<div class="category-grid">
    <?php foreach ($categories as $cat): ?>
        <a href="category.php?id=<?= (int)$cat['category_id'] ?>" class="category-card">
            <img src="<?= e($cat['image_url']) ?>" alt="<?= e($cat['category_name']) ?>"
                 onerror="this.src='https://via.placeholder.com/300x200?text=Category'">
            <span><?= e($cat['category_name']) ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- ===== Sort / Filter ===== -->
<h2 class="section-title">Popular Products</h2>
<form class="sort-bar" method="GET" action="index.php">
    <label>Category:
        <select name="cat" onchange="this.form.submit()">
            <option value="0">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['category_id'] ?>" <?= $cat_filter === (int)$cat['category_id'] ? 'selected' : '' ?>>
                    <?= e($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Sort by:
        <select name="sort" onchange="this.form.submit()">
            <?= sort_select($sort) ?>
        </select>
    </label>
</form>

<?php render_product_grid($products, $_SERVER['REQUEST_URI']); ?>

<?php include 'footer.php'; ?>