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
$stmt = $conn->query("
    SELECT p.*, c.name as category_name, s.name as supplier_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll();
?>


<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-box"></i> Data Produk</h2>
    </div>
    <div class="content-header-right">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Produk</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-barcode"></i> Kode</th>
                <th><i class="fas fa-tag"></i> Nama</th>
                <th><i class="fas fa-list-alt"></i> Kategori</th>
                <th><i class="fas fa-truck"></i> Supplier</th>
                <th><i class="fas fa-boxes"></i> Stok</th>
                <th><i class="fas fa-shopping-cart"></i> Harga Beli</th>
                <th><i class="fas fa-dollar-sign"></i> Harga Jual</th>
                <th><i class="fas fa-cogs"></i> Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['code']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['category_name']; ?></td>
                    <td><?php echo $product['supplier_name']; ?></td>
                    <td>
                        <?php echo $product['stock']; ?>
                        <?php if ($product['stock'] <= $product['min_stock']): ?>
                            <span class="badge badge-danger">Low</span>
                        <?php endif; ?>
                    </td>
                    <td>Rp <?php echo number_format($product['buy_price'], 0, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($product['sell_price'], 0, ',', '.'); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>