<?php
require_once __DIR__ . '/auth.php';

/* ---------- Delete category ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->execute([(int)$_POST['delete_id']]);
    set_flash('🗑️ Category deleted (and all its products).');
    redirect('categories.php');
}

$categories = $pdo->query("
    SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id) AS product_count
    FROM categories c
    ORDER BY c.category_name
")->fetchAll();

$page_title = 'Categories';
$active     = 'categories';
include __DIR__ . '/header.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>All Categories (<?= count($categories) ?>)</h2>
        <a href="category_form.php" class="btn btn-sm">➕ Add Category</a>
    </div>

    <div class="table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th><th>Category</th><th>Description</th>
                    <th>Products</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="5" class="empty">No categories yet.</td></tr>
            <?php else: foreach ($categories as $c): ?>
                <tr>
                    <td><img src="<?= e($c['image_url']) ?>" alt=""
                             onerror="this.src='https://via.placeholder.com/60?text=IMG'"></td>
                    <td><?= e($c['category_name']) ?></td>
                    <td><?= e(mb_strimwidth($c['description'] ?? '', 0, 60, '…')) ?></td>
                    <td><?= (int)$c['product_count'] ?></td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-sm btn-outline"
                               href="category_form.php?id=<?= (int)$c['category_id'] ?>">Edit</a>

                            <form method="POST" action="categories.php"
                                  onsubmit="return confirm('Delete this category? ALL <?= (int)$c['product_count'] ?> product(s) inside it will also be deleted.');">
                                <input type="hidden" name="delete_id" value="<?= (int)$c['category_id'] ?>">
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