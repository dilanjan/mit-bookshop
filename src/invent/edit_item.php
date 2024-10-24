<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../../index.php');
    exit;
}

// Fetch all suppliers for the supplier dropdown
$suppliers_stmt = $conn->prepare("SELECT supplier_id, supplier_name FROM suppliers;");
$suppliers_stmt->execute();
$suppliers = $suppliers_stmt->get_result();

// Define category options
$categories = [
    'Books' => 'Books',
    'Notebooks' => 'Notebooks',
    'Writing Instruments' => 'Writing Instruments',
    'Paper Products' => 'Paper Products',
    'Office Supplies' => 'Office Supplies',
    'Art Supplies' => 'Art Supplies',
    'School Supplies' => 'School Supplies',
    'Calendars & Planners' => 'Calendars & Planners',
    'Gift products' => 'Gift products',
    'Technology Accessories' => 'Technology Accessories'
];

// Fetch item details
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?;");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if (!$item) {
        $_SESSION['error'] = "product not found.";
        header('Location: ../../inventory.php');
        exit;
    }

    // Initialize variables with existing values
    $product_name = $item['product_name'];
    $category = $item['category'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $selling_price = $item['selling_price'];
    $supplier_id = $item['supplier_id'];

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_name = $_POST['product_name'];
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];
        $price = floatval(str_replace(',', '', $_POST['price']));
        $selling_price = floatval(str_replace(',', '', $_POST['selling_price']));
        $supplier_id = $_POST['supplier_id'];

        // Validate input
        if (!isset($categories[$category])) {
            $_SESSION['error'] = "Invalid category selected.";
        } elseif ($quantity < 1 || $quantity > 10000000) {
            $_SESSION['error'] = "Quantity must be between 1 and 10,000,000.";
        } elseif ($price < 0.01 || $price > 1e21) {
            $_SESSION['error'] = "Buy Price must be between 0.01 and 1,000,000,000,000,000,000,000.";
        } elseif ($selling_price < 0.01 || $selling_price > 1e21) {
            $_SESSION['error'] = "Selling Price must be between 0.01 and 1,000,000,000,000,000,000,000.";
        } else {
            // Update item details
            $stmt = $conn->prepare("UPDATE products SET product_name=?, category=?, quantity=?, price=?, selling_price=?, supplier_id=?, updated_at=NOW() WHERE id=?;");
            $stmt->bind_param("ssiddii", $product_name, $category, $quantity, $price, $selling_price, $supplier_id, $item_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "product updated successfully.";
                header('Location: ../../inventory.php');
                exit;
            } else {
                $_SESSION['error'] = "Error updating item: " . $conn->error;
            }
        }
    }
} else {
    $_SESSION['error'] = "No item ID provided.";
    header('Location: ../../inventory.php');
    exit;
}
?>

<div class="container vh-100">
    <div class="row h-100 align-products-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Edit product</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="product_name" class="form-label">product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $key => $value): ?>
                            <option value="<?php echo $key; ?>" <?php echo $key == $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="10000000" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Buy Price (LKR)</label>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars(number_format(floatval($price), 2)); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="selling_price" class="form-label">Selling Price (LKR)</label>
                    <input type="text" class="form-control" id="selling_price" name="selling_price" value="<?php echo htmlspecialchars(number_format(floatval($selling_price), 2)); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                            <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo $supplier['supplier_id'] == $supplier_id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['supplier_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update product</button>
                <a href="../../inventory.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('price').addEventListener('input', function (e) {
    var value = e.target.value.replace(/,/g, '');
    if (value) {
        e.target.value = parseFloat(value).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
});

document.getElementById('selling_price').addEventListener('input', function (e) {
    var value = e.target.value.replace(/,/g, '');
    if (value) {
        e.target.value = parseFloat(value).toLocaleString('en', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
});
</script>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>