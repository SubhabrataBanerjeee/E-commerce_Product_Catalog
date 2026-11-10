<?php
require_once __DIR__ . '/auth.php';

$totalProducts   = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCategories = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalUsers      = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$recent = $pdo->query("
    SELECT p.product_id, p.product_name, p.price, p.image_url, c.category_name
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.product_id DESC
    LIMIT 6
")->fetchAll();

$page_title = 'Dashboard';
$active     = 'dashboard';
include __DIR__ . '/header.php';
?>

<div class="stat-grid">
    <div class="stat-card">
        <div class="label">Total Products</div>
        <div class="value"><?= $totalProducts ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Total Categories</div>
        <div class="value"><?= $totalCategories ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Registered Users</div>
        <div class="value"><?= $totalUsers ?></div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Recently Added Products</h2>
        <a href="products.php" class="btn btn-sm btn-outline">View all →</a>
    </div>
    <div class="table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="5" class="empty">No products yet. <a href="product_form.php">Add one →</a></td></tr>
            <?php else: foreach ($recent as $r): ?>
                <tr>
                    <td><img src="<?= e($r['image_url']) ?>" alt=""
                             onerror="this.src='https://via.placeholder.com/60?text=IMG'"></td>
                    <td><?= e($r['product_name']) ?></td>
                    <td><?= e($r['category_name']) ?></td>
                    <td>₹<?= number_format($r['price'], 2) ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline"
                           href="product_form.php?id=<?= (int)$r['product_id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>