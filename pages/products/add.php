<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';



$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("
            INSERT INTO products (code, name, category_id, supplier_id, stock, min_stock, buy_price, sell_price, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            sanitize($_POST['code']),
            sanitize($_POST['name']),
            $_POST['category_id'],
            $_POST['supplier_id'],
            $_POST['stock'],
            $_POST['min_stock'],
            $_POST['buy_price'],
            $_POST['sell_price'],
            sanitize($_POST['description'])
        ]);

        // Record stock movement if initial stock > 0
        if ($_POST['stock'] > 0) {
            $product_id = $conn->lastInsertId();
            $stmt = $conn->prepare("
                INSERT INTO stock_movements (product_id, type, quantity, notes, user_id)
                VALUES (?, 'in', ?, 'Stok awal', ?)
            ");
            $stmt->execute([$product_id, $_POST['stock'], $_SESSION['user_id']]);
        }

        // Set success message in session
        $_SESSION['success_message'] = "Produk berhasil ditambahkan";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-plus-circle"></i> Tambah Produk</h2>
    </div>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-grid">
            <div>
                <div class="form-group">
                    <label>Kode Produk</label>
                    <input type="text" name="code" required>
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo $category['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id">
                        <option value="">Pilih Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?php echo $supplier['id']; ?>">
                                <?php echo $supplier['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stock" value="0" required>
                </div>
                <div class="form-group">
                    <label>Minimal Stok</label>
                    <input type="number" name="min_stock" value="10" required>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" name="buy_price" required>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" name="sell_price" required>
                </div>
            </div>

            <div class="form-group full-width">
                <label>Deskripsi</label>
                <textarea name="description" rows="4"></textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan</button>
                <a href="index.php" class="btn btn-warning">
                    <i class="fa-solid fa-xmark"></i>Batal</a>
            </div>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>