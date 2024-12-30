<?php

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit();
}


$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("
            UPDATE products 
            SET code = ?, name = ?, category_id = ?, supplier_id = ?, 
                min_stock = ?, buy_price = ?, sell_price = ?, description = ?
            WHERE id = ?
        ");

        $stmt->execute([
            sanitize($_POST['code']),
            sanitize($_POST['name']),
            $_POST['category_id'],
            $_POST['supplier_id'],
            $_POST['min_stock'],
            $_POST['buy_price'],
            $_POST['sell_price'],
            sanitize($_POST['description']),
            $id
        ]);

        $_SESSION['success_message'] = "Produk berhasil diedit";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <h2>Edit Produk</h2>
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
                    <input type="text" name="code" value="<?php echo $product['code']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $supplier['id']; ?>"
                                <?php echo ($supplier['id'] == $product['supplier_id']) ? 'selected' : ''; ?>>
                                <?php echo $supplier['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div>
                <div class="form-group">
                    <label>Minimal Stok</label>
                    <input type="number" name="min_stock" value="<?php echo $product['min_stock']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" name="buy_price" value="<?php echo $product['buy_price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" name="sell_price" value="<?php echo $product['sell_price']; ?>" required>
                </div>
            </div>


            <div class="form-group full-width">
                <label>Deskripsi</label>
                <textarea name="description" rows="4"><?php echo $product['description']; ?></textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="index.php" class="btn btn-warning">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>