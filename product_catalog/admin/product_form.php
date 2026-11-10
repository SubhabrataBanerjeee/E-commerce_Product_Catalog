<?php
require_once __DIR__ . '/auth.php';

$id      = (int)($_GET['id'] ?? 0);
$editing = $id > 0;

$categories = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name")->fetchAll();

/* ---------- Load existing product ---------- */
if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        set_flash('Product not found.');
        redirect('products.php');
    }
} else {
    $product = [
        'product_name' => '',
        'category_id'  => '',
        'price'        => '',
        'description'  => '',
        'image_url'    => '',
    ];
}

$error = '';

/* ---------- Save ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['product_name'] = trim($_POST['product_name'] ?? '');
    $product['category_id']  = (int)($_POST['category_id'] ?? 0);
    $product['price']        = trim($_POST['price'] ?? '');
    $product['description']  = trim($_POST['description'] ?? '');
    $product['image_url']    = trim($_POST['image_url'] ?? '');

    if ($product['product_name'] === '') {
        $error = 'Product name is required.';
    } elseif ($product['category_id'] <= 0) {
        $error = 'Please select a category.';
    } elseif (!is_numeric($product['price']) || (float)$product['price'] <= 0) {
        $error = 'Please enter a valid price greater than 0.';
    }

    if ($error === '') {
        $img   = $product['image_url'] !== ''
                 ? $product['image_url']
                 : 'https://via.placeholder.com/300x200?text=Product';
        $price = (float)$product['price'];

        if ($editing) {
            $stmt = $pdo->prepare("UPDATE products
                                   SET category_id = ?, product_name = ?, description = ?, price = ?, image_url = ?
                                   WHERE product_id = ?");
            $stmt->execute([$product['category_id'], $product['product_name'],
                            $product['description'], $price, $img, $id]);
            set_flash('✅ Product updated successfully.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, description, price, image_url)
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$product['category_id'], $product['product_name'],
                            $product['description'], $price, $img]);
            set_flash('✅ Product added successfully.');
        }
        redirect('products.php');
    }
}

$page_title = $editing ? 'Edit Product' : 'Add Product';
$active     = 'products';
include __DIR__ . '/header.php';
?>

<div class="form-card">
    <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

    <?php if (empty($categories)): ?>
        <p class="empty">You need at least one category first.
            <a href="category_form.php" style="color:#0c831f;font-weight:700;">Create one →</a>
        </p>
    <?php else: ?>
    <form method="POST">
        <label>Product Name *</label>
        <input type="text" name="product_name" value="<?= e($product['product_name']) ?>" required>

        <div class="form-row">
            <div>
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">— Select category —</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['category_id'] ?>"
                            <?= (int)$product['category_id'] === (int)$c['category_id'] ? 'selected' : '' ?>>
                            <?= e($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Price (₹) *</label>
                <input type="number" step="0.01" min="0.01" name="price"
                       value="<?= e($product['price']) ?>" required>
            </div>
        </div>

        <label>Description</label>
        <textarea name="description" rows="4"><?= e($product['description']) ?></textarea>

        <label>Image URL</label>
        <input type="text" name="image_url" value="<?= e($product['image_url']) ?>"
               placeholder="https://... (leave blank for placeholder)">
        <p class="hint">Leave blank to use the default placeholder image.</p>

        <div class="form-actions">
            <button type="submit" class="btn"><?= $editing ? 'Save Changes' : 'Add Product' ?></button>
            <a href="products.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>