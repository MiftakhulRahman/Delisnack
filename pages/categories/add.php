<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([
            sanitize($_POST['name']),
            sanitize($_POST['description'])
        ]);

        $_SESSION['success_message'] = "Kategori berhasil ditambahkan";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-plus-circle"></i> Tambah Kategori</h2>
    </div>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="4"></textarea>
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