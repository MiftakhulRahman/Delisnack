<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("
            INSERT INTO suppliers (name, address, phone, email) 
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            sanitize($_POST['name']),
            sanitize($_POST['address']),
            sanitize($_POST['phone']),
            sanitize($_POST['email'])
        ]);

        $_SESSION['success_message'] = "Supplier berhasil ditambahkan";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-plus-circle"></i> Tambah Supplier</h2>
    </div>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Supplier</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div class="button-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan</button>
            <a href="index.php" class="btn btn-warning">
                <i class="fa-solid fa-xmark"></i>Batal</a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>