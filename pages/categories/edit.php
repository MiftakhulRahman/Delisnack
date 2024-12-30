<?php
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([
            sanitize($_POST['name']),
            sanitize($_POST['description']),
            $id
        ]);

        $_SESSION['success_message'] = "Kategori berhasil diedit";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <h2>Edit Kategori</h2>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" value="<?php echo $category['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="4"><?php echo $category['description']; ?></textarea>
        </div>
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-warning">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>