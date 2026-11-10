<?php
/* ---------- Output escaping ---------- */
function e($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/* ---------- Redirect ---------- */
function redirect($url) {
    header("Location: $url");
    exit;
}

function safe_redirect($url, $default = 'index.php') {
    $url = trim((string)$url);
    if ($url === '' || preg_match('#^(https?:)?//#i', $url) || stripos($url, 'javascript:') === 0) {
        return $default;
    }
    return $url;
}

/* ---------- Flash messages ---------- */
function set_flash($msg) { $_SESSION['flash'] = $msg; }

function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return null;
}

/* ---------- Auth (customers) ---------- */
function is_logged_in() { return isset($_SESSION['user_id']); }
function current_user_name() { return $_SESSION['user_name'] ?? ''; }

/* ---------- Cart ---------- */
function cart_count() {
    $n = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $q) $n += (int)$q;
    }
    return $n;
}

/* ---------- Sorting (whitelisted, SQL-injection safe) ---------- */
function sort_sql($sort) {
    $map = [
        'newest'     => 'p.product_id DESC',
        'price_asc'  => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'name_asc'   => 'p.product_name ASC',
        'name_desc'  => 'p.product_name DESC',
    ];
    return $map[$sort] ?? $map['newest'];
}

function sort_select($current) {
    $options = [
        'newest'     => 'Newest First',
        'price_asc'  => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'name_asc'   => 'Name: A to Z',
        'name_desc'  => 'Name: Z to A',
    ];
    $html = '';
    foreach ($options as $val => $label) {
        $sel = ($current === $val) ? 'selected' : '';
        $html .= "<option value=\"$val\" $sel>$label</option>";
    }
    return $html;
}

/* ---------- Reusable product grid (with Add to Cart button) ---------- */
function render_product_grid($products, $redirect = '') {
    if (empty($products)) {
        echo '<p class="empty-msg">😕 No products found.</p>';
        return;
    }
    echo '<div class="product-grid">';
    foreach ($products as $row) {
        $id = (int)$row['product_id'];
        echo '<div class="product-card">';
        echo '<a class="card-link" href="product.php?id=' . $id . '">';
        echo '<img src="' . e($row['image_url']) . '" alt="' . e($row['product_name']) . '"'
           . ' onerror="this.src=\'https://via.placeholder.com/300x200?text=No+Image\'">';
        echo '<div class="name">' . e($row['product_name']) . '</div>';
        if (!empty($row['category_name'])) {
            echo '<div class="desc">' . e($row['category_name']) . '</div>';
        }
        echo '</a>';
        echo '<div class="price">₹' . number_format($row['price'], 2) . '</div>';
        echo '<form action="add_to_cart.php" method="POST" class="add-form">';
        echo '<input type="hidden" name="product_id" value="' . $id . '">';
        echo '<input type="hidden" name="qty" value="1">';
        echo '<input type="hidden" name="redirect" value="' . e($redirect) . '">';
        echo '<button type="submit" class="add-btn">ADD</button>';
        echo '</form>';
        echo '</div>';
    }
    echo '</div>';
}

/* ---------- Admin auth (NEW) ---------- */
function is_admin_logged_in() { return !empty($_SESSION['admin_id']); }
function current_admin_name() { return $_SESSION['admin_name'] ?? ''; }