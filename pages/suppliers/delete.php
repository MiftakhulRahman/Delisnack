<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    $supplier = $stmt->fetch();

    if (!$supplier) {
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE supplier_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        die("Supplier tidak dapat dihapus karena masih digunakan di produk");
    }

    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success_message'] = "Supplier berhasil dihapus";
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}