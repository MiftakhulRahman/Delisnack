<?php
// generate invoice number
function generateInvoiceNumber()
{
    $prefix = "INV";
    $year = date('Y');
    $month = date('m');
    $day = date('d');

    // Get last invoice number
    global $conn;
    $sql = "SELECT invoice_number FROM sales 
            WHERE DATE(date) = CURDATE() 
            ORDER BY id DESC LIMIT 1";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastNumber = substr($row['invoice_number'], -4);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $nextNumber = '0001';
    }

    return $prefix . $year . $month . $day . $nextNumber;
}

// mengupdate stok
function updateProductStock($product_id, $quantity, $type, $notes)
{
    global $conn;

    $stockChange = ($type === 'out') ? -$quantity : $quantity;

    // Update stok produk
    $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$stockChange, $product_id]);

    if ($stmt->rowCount() > 0) {
        // Catat pergerakan stok
        logStockMovement($product_id, $quantity, $type, $notes);
        return true;
    } else {
        return false;
    }
}

function logStockMovement($product_id, $quantity, $type, $notes)
{
    global $conn;

    // Gunakan CURRENT_TIMESTAMP dari database langsung
    $sql = "INSERT INTO stock_movements (product_id, quantity, type, notes, date, user_id) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id, $quantity, $type, $notes, $_SESSION['user_id']]);

    return $stmt->rowCount() > 0;
}

// Format currency
function formatCurrency($amount)
{
    return "Rp " . number_format($amount, 0, ',', '.');
}
