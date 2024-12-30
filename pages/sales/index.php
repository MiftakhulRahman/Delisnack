<?php

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}


$sql = "SELECT s.*, u.name as user_name 
        FROM sales s 
        JOIN users u ON s.user_id = u.id 
        ORDER BY s.date DESC";
$stmt = $conn->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-shopping-cart"></i> Data Penjualan</h2>
    </div>
    <div class="content-header-right">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Penjualan
        </a>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Penjualan</h3>
    </div>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th><i class="fas fa-receipt"></i> No Invoice</th>
                <th><i class="fas fa-calendar"></i> Tanggal</th>
                <th><i class="fas fa-money-bill-wave"></i> Total</th>
                <th><i class="fas fa-user"></i> User</th>
                <th><i class="fas fa-sticky-note"></i> Catatan</th>
                <th><i class="fas fa-cogs"></i> Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?php echo $row['invoice_number']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['date'])); ?></td>
                    <td><?php echo formatCurrency($row['total_amount']); ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['notes']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i>
                                <p>Hapus</p>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include '../../includes/footer.php'; ?>