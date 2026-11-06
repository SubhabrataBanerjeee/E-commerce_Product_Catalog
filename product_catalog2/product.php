<?php
require_once 'config.php';
require_once 'functions.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT p.*, c.category_name, c.category_id
                       FROM products p JOIN categories c ON p.category_id = c.category_id
                       WHERE p.product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    die("Product not found. <a href='index.php'>Go home</a>");
}

// Related products from same category
$stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p
                       JOIN categories c ON p.category_id = c.category_id
                       WHERE p.category_id = ? AND p.product_id != ?
                       LIMIT 4");
$stmt->execute([$product['category_id'], $id]);
$related = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product['product_name']) ?> - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="product-detail">
    <img src="<?= e($product['image_url']) ?>" alt="<?= e($product['product_name']) ?>"
         onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">

    <h1><?= e($product['product_name']) ?></h1>
    <p style="color:#666;font-size:14px;">
        Category:
        <a href="category.php?id=<?= (int)$product['category_id'] ?>" style="color:#0c831f;font-weight:600;">
            <?= e($product['category_name']) ?>
        </a>
    </p>

    <div class="price">₹<?= number_format($product['price'], 2) ?></div>
    <p class="description"><?= nl2br(e($product['description'])) ?></p>

    <form action="add_to_cart.php" method="POST" class="buy-row">
        <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
        <input type="hidden" name="redirect" value="<?= e($_SERVER['REQUEST_URI']) ?>">
        <input type="number" name="qty" value="1" min="1" max="20">
        <button type="submit" class="btn">🛒 Add to Cart</button>
    </form>
</div>

<?php if (count($related) > 0): ?>
    <h2 class="section-title">More from <?= e($product['category_name']) ?></h2>
    <?php render_product_grid($related, $_SERVER['REQUEST_URI']); ?>
<?php endif; ?>

<a href="index.php" class="back-link">← Back to Home</a>

<?php include 'footer.php'; ?>