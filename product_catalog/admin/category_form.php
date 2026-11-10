<?php
require_once __DIR__ . '/auth.php';

$id      = (int)($_GET['id'] ?? 0);
$editing = $id > 0;

if ($editing) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch();
    if (!$category) {
        set_flash('Category not found.');
        redirect('categories.php');
    }
} else {
    $category = ['category_name' => '', 'description' => '', 'image_url' => ''];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category['category_name'] = trim($_POST['category_name'] ?? '');
    $category['description']   = trim($_POST['description'] ?? '');
    $category['image_url']     = trim($_POST['image_url'] ?? '');

    if ($category['category_name'] === '') {
        $error = 'Category name is required.';
    }

    if ($error === '') {
        $img = $category['image_url'] !== ''
             ? $category['image_url']
             : 'https://via.placeholder.com/300x200?text=Category';

        if ($editing) {
            $stmt = $pdo->prepare("UPDATE categories SET category_name = ?, description = ?, image_url = ?
                                   WHERE category_id = ?");
            $stmt->execute([$category['category_name'], $category['description'], $img, $id]);
            set_flash('✅ Category updated successfully.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name, description, image_url)
                                   VALUES (?, ?, ?)");
            $stmt->execute([$category['category_name'], $category['description'], $img]);
            set_flash('✅ Category added successfully.');
        }
        redirect('categories.php');
    }
}

$page_title = $editing ? 'Edit Category' : 'Add Category';
$active     = 'categories';
include __DIR__ . '/header.php';
?>

<div class="form-card">
    <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

    <form method="POST">
        <label>Category Name *</label>
        <input type="text" name="category_name" value="<?= e($category['category_name']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="3"><?= e($category['description'] ?? '') ?></textarea>

        <label>Image URL</label>
        <input type="text" name="image_url" value="<?= e($category['image_url']) ?>"
               placeholder="https://... (leave blank for placeholder)">
        <p class="hint">Leave blank to use the default placeholder image.</p>

        <div class="form-actions">
            <button type="submit" class="btn"><?= $editing ? 'Save Changes' : 'Add Category' ?></button>
            <a href="categories.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/footer.php'; ?>