<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch();

if (!$supplier) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("
            UPDATE suppliers 
            SET name = ?, address = ?, phone = ?, email = ?
            WHERE id = ?
        ");

        $stmt->execute([
            sanitize($_POST['name']),
            sanitize($_POST['address']),
            sanitize($_POST['phone']),
            sanitize($_POST['email']),
            $id
        ]);

        $_SESSION['success_message'] = "Supplier berhasil diedit";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <h2>Edit Supplier</h2>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Supplier</label>
            <input type="text" name="name" value="<?php echo $supplier['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3"><?php echo $supplier['address']; ?></textarea>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" value="<?php echo $supplier['phone']; ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $supplier['email']; ?>">
        </div>
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-warning">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>