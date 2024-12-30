<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';


if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success_message']; 
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

<?php

$stmt = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");
$suppliers = $stmt->fetchAll();
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-truck"></i> Data Supplier</h2>
    </div>
    <div class="content-header-right">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Supplier
        </a>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Supplier</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Nama</th>
                <th><i class="fas fa-map-marker-alt"></i> Alamat</th>
                <th><i class="fas fa-phone"></i> Telepon</th>
                <th><i class="fas fa-envelope"></i> Email</th>
                <th><i class="fas fa-calendar-alt"></i> Tanggal Dibuat</th>
                <th><i class="fas fa-cogs"></i> Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?php echo $supplier['name']; ?></td>
                    <td><?php echo $supplier['address']; ?></td>
                    <td><?php echo $supplier['phone']; ?></td>
                    <td><?php echo $supplier['email']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($supplier['created_at'])); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $supplier['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $supplier['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>