<?php
$nav_categories = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name")->fetchAll();
$header_q   = $_GET['q'] ?? '';
$flash_msg  = get_flash();
?>
<div class="header">
    <a href="index.php" class="logo">⚡ QuickCart</a>

    <form class="search-bar" action="search.php" method="GET">
        <input type="text" name="q" value="<?= e($header_q) ?>" placeholder="Search for milk, chips, drinks...">
        <button type="submit">Search</button>
    </form>

    <nav class="nav-links">
        <a href="index.php">Home</a>

        <div class="dropdown">
            <span class="dropbtn">Categories ▾</span>
            <div class="dropdown-content">
                <?php foreach ($nav_categories as $c): ?>
                    <a href="category.php?id=<?= (int)$c['category_id'] ?>"><?= e($c['category_name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <a href="cart.php" class="cart-link">🛒 Cart <span class="cart-badge"><?= cart_count() ?></span></a>

        <?php if (is_logged_in()): ?>
            <span class="user-greeting">👤 <?= e(current_user_name()) ?></span>
            <a href="logout.php" class="btn-outline">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn-solid">Register</a>
        <?php endif; ?>
    </nav>
</div>

<?php if ($flash_msg): ?>
    <div class="flash"><?= e($flash_msg) ?></div>
<?php endif; ?>