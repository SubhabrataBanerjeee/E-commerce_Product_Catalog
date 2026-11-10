<?php
require_once __DIR__ . '/auth.php';

/* ---------- Delete product ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([(int)$_POST['delete_id']]);
    set_flash('🗑️ Product deleted successfully.');
    redirect('products.php');
}

/* ---------- Search / filter ---------- */
$q      = trim($_GET['q'] ?? '');
$cat_id = (int)($_GET['cat'] ?? 0);

$sql    = "SELECT p.*, c.category_name
           FROM products p
           JOIN categories c ON p.category_id = c.category_id
           WHERE 1=1";
$params = [];

if ($q !== '') {
    $sql .= " AND p.product_name LIKE ?";
    $params[] = '%' . $q . '%';
}
if ($cat_id > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $cat_id;
}
$sql .= " ORDER BY p.product_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name")->fetchAll();

$page_title = 'Products';
$active     = 'products';
include __DIR__ . '/header.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>All Products (<?= count($products) ?>)</h2>
        <a href="product_form.php" class="btn btn-sm">➕ Add Product</a>
    </div>

    <form class="toolbar" method="GET" action="products.php">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search product name...">
        <select name="cat">
            <option value="0">All Categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['category_id'] ?>" <?= $cat_id === (int)$c['category_id'] ? 'selected' : '' ?>>
                    <?= e($c['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm">Filter</button>
        <?php if ($q !== '' || $cat_id > 0): ?>
            <a href="products.php" class="btn btn-sm btn-outline">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th><th>Product</th><th>Category</th>
                    <th>Price</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="5" class="empty">No products found.</td></tr>
            <?php else: foreach ($products as $p): ?>
                <tr>
                    <td><img src="<?= e($p['image_url']) ?>" alt=""
                             onerror="this.src='https://via.placeholder.com/60?text=IMG'"></td>
                    <td><?= e($p['product_name']) ?></td>
                    <td><?= e($p['category_name']) ?></td>
                    <td>₹<?= number_format($p['price'], 2) ?></td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-sm btn-outline"
                               href="product_form.php?id=<?= (int)$p['product_id'] ?>">Edit</a>

                            <form method="POST" action="products.php"
                                  onsubmit="return confirm('Delete this product permanently?');">
                                <input type="hidden" name="delete_id" value="<?= (int)$p['product_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger-outline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>