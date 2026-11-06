<div class="footer">
    <div class="footer-grid">
        <div>
            <h3>⚡ QuickCart</h3>
            <p>Groceries and daily essentials delivered to your door in minutes. Fresh, fast and always in stock.</p>
        </div>
        <div>
            <h4>Shop</h4>
            <a href="index.php">Home</a>
            <a href="search.php?q=milk">Dairy</a>
            <a href="search.php?q=chips">Snacks</a>
            <a href="search.php?q=drinks">Drinks</a>
        </div>
        <div>
            <h4>Categories</h4>
            <?php
            $footer_cats = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_id LIMIT 5")->fetchAll();
            foreach ($footer_cats as $fc) {
                echo '<a href="category.php?id=' . (int)$fc['category_id'] . '">' . e($fc['category_name']) . '</a>';
            }
            ?>
        </div>
        <div>
            <h4>Account</h4>
            <a href="cart.php">My Cart</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="admin/index.php">Admin Panel</a>
        </div>
    </div>
    <div class="footer-bottom">
        © <?= date('Y') ?> QuickCart — All rights reserved. Built with PHP &amp; MySQL.
    </div>
</div>
</body>
</html>