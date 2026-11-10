<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

unset($_SESSION['admin_id'], $_SESSION['admin_name']);
set_flash('👋 You have been logged out of the admin panel.');
redirect('login.php');