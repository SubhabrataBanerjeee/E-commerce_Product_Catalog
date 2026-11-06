<?php
require_once 'config.php';
require_once 'functions.php';

$query  = trim($_GET['q'] ?? '');
$sort   = $_GET['sort'] ?? 'newest';
$results = [];

if ($query !== '') {
    // Split into words so "milk chips drinks" finds milk AND chips AND drinks
    $stop_words = ['a','an','the','and','or','for','of','with','in','on','to'];
    $terms = preg_split('/[\s,]+/', strtolower($query));
    $terms = array_values(array_unique(array_filter($terms, function ($t) use ($stop_words) {
        return $t !== '' && !in_array($t, $stop_words, true);
    })));
    if (empty($terms)) { $terms = [$query]; }

    $conditions = [];
    $params = [];
    foreach ($terms as $t) {
        $conditions[] = "(p.product_name LIKE ? OR p.description LIKE ? OR c.category_name LIKE ?)";
        $like = '%' . $t . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $sql = "SELECT p.*, c.category_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            WHERE " . implode(' OR ', $conditions) . "
            ORDER BY " . sort_sql($sort);

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<h2 class="section-title">
    Search results for: "<?= e($query) ?>"
    <span style="font-size:13px;color:#888;font-weight:400;">(<?= count($results) ?> found)</span>
</h2>

<?php if ($query !== '' && count($results) > 0): ?>
    <form class="sort-bar" method="GET" action="search.php">
        <input type="hidden" name="q" value="<?= e($query) ?>">
        <label>Sort by:
            <select name="sort" onchange="this.form.submit()">
                <?= sort_select($sort) ?>
            </select>
        </label>
    </form>
<?php endif; ?>

<?php render_product_grid($results, $_SERVER['REQUEST_URI']); ?>

<a href="index.php" class="back-link">← Back to Home</a>

<?php include 'footer.php'; ?>