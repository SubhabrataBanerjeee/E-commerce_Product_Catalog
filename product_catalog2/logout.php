<?php
require_once 'config.php';
require_once 'functions.php';

unset($_SESSION['user_id'], $_SESSION['user_name']);
set_flash('👋 You have been logged out. See you soon!');
redirect('index.php');