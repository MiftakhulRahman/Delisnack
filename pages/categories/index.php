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

$stmt = $conn->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-tags"></i> Data Kategori</h2>
    </div>
    <div class="content-header-right">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
</div>
<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Kategori</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-tag"></i> Nama</th>
                <th><i class="fas fa-file-alt"></i> Deskripsi</th>
                <th><i class="fas fa-calendar-alt"></i> Tanggal Dibuat</th>
                <th><i class="fas fa-cogs"></i> Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category['name']; ?></td>
                    <td><?php echo $category['description']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php require_once '../../includes/footer.php'; ?>