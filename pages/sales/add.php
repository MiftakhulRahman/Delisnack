<?php

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';


date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}


$sql = "SELECT id, code, name, sell_price, stock FROM products WHERE stock > 0";
$stmt = $conn->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);



if (isset($_POST['submit'])) {
    $invoice_number = generateInvoiceNumber();
    $total_amount = 0;
    $user_id = $_SESSION['user_id'];
    $notes = $_POST['notes'];

    try {
        $conn->beginTransaction();


        $sql = "INSERT INTO sales (invoice_number, total_amount, user_id, notes) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$invoice_number, $total_amount, $user_id, $notes]);
        $sale_id = $conn->lastInsertId();


        foreach ($_POST['products'] as $item) {
            if (empty($item['product_id']) || empty($item['quantity'])) continue;

            $product_id = $item['product_id'];
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            $subtotal = $quantity * $price;

            // Validasi stok
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $currentStock = $stmt->fetchColumn();

            if ($currentStock < $quantity) {
                throw new Exception("Stok produk tidak cukup untuk produk ID: $product_id!");
            }

            $total_amount += $subtotal;


            $sql = "INSERT INTO sales_details (sale_id, product_id, quantity, price, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$sale_id, $product_id, $quantity, $price, $subtotal]);


            $notes = "Penjualan #" . $invoice_number;
            

            $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$quantity, $product_id]);

            if ($stmt->rowCount() > 0) {

                logStockMovement($product_id, $quantity, 'out', $notes);
            } else {
                throw new Exception("Gagal update stok produk ID: $product_id!");
            }
        }


        $sql = "UPDATE sales SET total_amount = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$total_amount, $sale_id]);

        $conn->commit();
        $_SESSION['success'] = "Penjualan berhasil disimpan!";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-plus-circle"></i> Tambah Penjualan</h2>
    </div>
</div>

<div class="form-container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" id="salesForm" class="form-grid">
        <div id="productList" class="full-width">
            <div class="product-item form-grid">
                <div class="form-group">
                    <label for="product_0">Pilih Produk</label>
                    <select name="products[0][product_id]" id="product_0" class="product-select" required>
                        <option value="">Pilih Produk</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['id']; ?>"
                                data-price="<?php echo $product['sell_price']; ?>"
                                data-stock="<?php echo $product['stock']; ?>">
                                <?php echo $product['code'] . ' - ' . $product['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity_0">Jumlah</label>
                    <input type="number" id="quantity_0" name="products[0][quantity]"
                        class="quantity" placeholder="Jumlah" min="1" required>
                </div>

                <div class="form-group">
                    <label for="price_0">Harga</label>
                    <input type="number" id="price_0" name="products[0][price]"
                        class="price" placeholder="Harga" readonly>
                </div>

                <div class="form-group">
                    <label for="subtotal_0">Subtotal</label>
                    <input type="text" id="subtotal_0" class="subtotal" placeholder="Subtotal" readonly>
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-product">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group full-width">
            <button type="button" id="addProduct" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>

        <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea id="notes" name="notes" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="grandTotal">Total</label>
            <div class="input-group">
                <input type="text" id="grandTotal" class="form-control" readonly>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let productCount = 0;

        function calculateSubtotal(row) {
            const quantity = parseInt(row.find('.quantity').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const subtotal = quantity * price;
            row.find('.subtotal').val(subtotal.toFixed(2));

            let grandTotal = 0;
            $('.subtotal').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grandTotal').val(grandTotal.toFixed(2));
        }

        $('#addProduct').click(function() {
            productCount++;
            const newRow = $('.product-item').first().clone();
            newRow.find('select').attr('name', `products[${productCount}][product_id]`).val('');
            newRow.find('.quantity').attr('name', `products[${productCount}][quantity]`).val('');
            newRow.find('.price').attr('name', `products[${productCount}][price]`).val('');
            newRow.find('.subtotal').val('');
            $('#productList').append(newRow);
        });

        $(document).on('click', '.remove-product', function() {
            if ($('.product-item').length > 1) {
                $(this).closest('.product-item').remove();
                calculateSubtotal($('.product-item'));
            }
        });

        $(document).on('change', '.product-select', function() {
            const row = $(this).closest('.product-item');
            const selectedOption = $(this).find('option:selected');
            const price = selectedOption.data('price');
            const stock = selectedOption.data('stock');

            row.find('.price').val(price);
            row.find('.quantity').attr('max', stock);
            calculateSubtotal(row);
        });

        $(document).on('change', '.quantity', function() {
            const row = $(this).closest('.product-item');
            const selectedOption = row.find('.product-select option:selected');
            const stock = selectedOption.data('stock');

            if (parseInt($(this).val()) > stock) {
                alert('Jumlah melebihi stok tersedia!');
                $(this).val(stock);
            }
            calculateSubtotal(row);
        });

        $('#salesForm').submit(function(e) {
            let isValid = false;
            $('.product-item').each(function() {
                const productId = $(this).find('.product-select').val();
                const quantity = $(this).find('.quantity').val();
                if (productId && quantity) {
                    isValid = true;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Silakan pilih minimal satu produk!');
            }
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>