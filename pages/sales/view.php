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


$sql = "SELECT s.*, u.name as user_name 
        FROM sales s 
        JOIN users u ON s.user_id = u.id 
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$sale_id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "SELECT sd.*, p.code, p.name 
        FROM sales_details sd
        JOIN products p ON sd.product_id = p.id
        WHERE sd.sale_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$sale_id]);
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-file-invoice"></i> Detail Invoice Penjualan</h2>
    </div>
    <div class="content-header-right">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="invoice-container">
    <div class="invoice-header">
        <div class="invoice-logo">
            <div class="image-text"><img src="/assets/img/logo-secondary.svg" alt="Logo Delisnack" width="60"><h1>DELISNACK</h1></div>
            <p>Jl. Sudirman No.25, Jakarta Selatan, 22567</p>
            <p>Telp: (055) 607-1780 | Email: contact@deslisnack.com</p>
        </div>
        <div class="invoice-details">
            <h2>INVOICE</h2>
            <table>
                <tr>
                    <th>Nomor Invoice:</th>
                    <td><?php echo $sale['invoice_number']; ?></td>
                </tr>
                <tr>
                    <th>Tanggal:</th>
                    <td><?php echo date('d/m/Y H:i', strtotime($sale['date'])); ?></td>
                </tr>
                <tr>
                    <th>Kasir:</th>
                    <td><?php echo $sale['user_name']; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="invoice-body">
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $total = 0;
                foreach ($details as $detail): 
                    $subtotal = $detail['quantity'] * $detail['price'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $detail['code']; ?></td>
                    <td><?php echo $detail['name']; ?></td>
                    <td><?php echo formatCurrency($detail['price']); ?></td>
                    <td><?php echo $detail['quantity']; ?></td>
                    <td><?php echo formatCurrency($subtotal); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                    <td colspan="2" class="total-amount">
                        <?php echo formatCurrency($sale['total_amount']); ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <?php if (!empty($sale['notes'])): ?>
        <div class="invoice-notes">
            <h3>Catatan:</h3>
            <p><?php echo htmlspecialchars($sale['notes']); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div class="invoice-footer">
        <div class="footer-section">
            <h3>Terima Kasih</h3>
            <p>Pembayaran lunas - Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>
</div>

<style>
    .invoice-container {
        background: white;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 20px;
    }

    .invoice-logo h1 {
        margin: 0;
        color: var(--primary-color);
    }

    .invoice-logo p {
        margin: 5px 0;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .invoice-logo .image-text {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
    }

    .invoice-details h2 {
        margin: 0 0 10px 0;
        color: var(--primary-color);
    }

    .invoice-details table {
        width: 100%;
    }

    .invoice-details th {
        text-align: left;
        width: 40%;
    }

    .invoice-items {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-items th, 
    .invoice-items td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .invoice-items thead {
        background-color: var(--bg-light);
    }

    .total-amount {
        font-weight: bold;
        text-align: right;
        font-size: 1.2rem;
        color: var(--primary-color);
    }

    .invoice-notes {
        margin-top: 20px;
        padding: 10px;
        background-color: var(--bg-light);
    }

    .invoice-footer {
        margin-top: 20px;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 20px;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .invoice-container, 
        .invoice-container * {
            visibility: visible;
        }
        .invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<?php include '../../includes/footer.php'; ?>