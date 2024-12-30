<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: index.php");
        exit();
    }


    $stmt = $conn->prepare("DELETE FROM stock_movements WHERE product_id = ?");
    $stmt->execute([$id]);


    $stmt = $conn->prepare("DELETE FROM sales_details WHERE product_id = ?");
    $stmt->execute([$id]);


    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success_message'] = "Produk berhasil dihapus";
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}