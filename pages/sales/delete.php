<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$sale_id = $_GET['id'];


try {
    $conn->beginTransaction();


    $sql = "SELECT product_id, quantity FROM sales_details WHERE sale_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$sale_id]);
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($details as $detail) {
        $notes = "Pembatalan penjualan #" . $sale_id;
        if (!updateProductStock($detail['product_id'], $detail['quantity'], 'in', $notes)) {
            throw new Exception("Gagal mengembalikan stok produk!");
        }
    }


    $sql = "DELETE FROM sales_details WHERE sale_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$sale_id]);


    $sql = "DELETE FROM sales WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$sale_id]);

    $conn->commit();
    $_SESSION['success'] = "Penjualan berhasil dihapus!";
} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Terjadi kesalahan! " . $e->getMessage();
}

header("Location: index.php");
exit();
