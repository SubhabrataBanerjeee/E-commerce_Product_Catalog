<?php
// Included at the top of every admin page. Blocks access if not logged in.
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (!is_admin_logged_in()) {
    redirect('login.php');
}