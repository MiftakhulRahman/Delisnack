<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'snack_inventory');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}


function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}


function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit();
    }
}
?>