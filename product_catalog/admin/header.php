<?php
// Expects $page_title and $active to be set before including.
$admin_nav = [
    'dashboard'  => ['index.php',      '📊 Dashboard'],
    'products'   => ['products.php',   '📦 Products'],
    'categories' => ['categories.php', '🗂️ Categories'],
];
$page_title = $page_title ?? 'Admin';
$active     = $active ?? '';
$flash      = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> · QuickCart Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="admin-brand">⚡ QuickCart</div>
        <nav class="admin-nav">
            <?php foreach ($admin_nav as $key => $item): ?>
                <a href="<?= $item[0] ?>" class="<?= $active === $key ? 'active' : '' ?>">
                    <?= $item[1] ?>
                </a>
            <?php endforeach; ?>
            <div class="sep"></div>
            <a href="logout.php">🚪 Logout</a>
        </nav>
        <a class="back" href="../index.php">← Back to Store</a>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h1><?= e($page_title) ?></h1>
            <div class="admin-user">👤 <?= e(current_admin_name()) ?></div>
        </div>

        <?php if ($flash): ?>
            <div class="flash"><?= e($flash) ?></div>
        <?php endif; ?>