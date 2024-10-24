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
    'Gift Items' => 'Gift Items',
    'Technology Accessories' => 'Technology Accessories'
];

// Initialize variables
$product_name = '';
$category = '';
$quantity = '';
$price = '';
$selling_price = '';
$supplier_id = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = floatval(str_replace(',', '', $_POST['price']));
    $selling_price = floatval(str_replace(',', '', $_POST['selling_price']));
    $supplier_id = $_POST['supplier_id'];

    // Validate input
    if (empty($product_name)) {
        $_SESSION['error'] = "Item name is required.";
    } elseif (strlen($product_name) < 3 || strlen($product_name) > 100) {
        $_SESSION['error'] = "Item name must be between 3 and 100 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9\s]+$/', $product_name)) {
        $_SESSION['error'] = "Item name can only contain letters, numbers, and spaces.";
    } elseif (!isset($categories[$category])) {
        $_SESSION['error'] = "Invalid category selected.";
    } elseif ($quantity < 1 || $quantity > 10000000) {
        $_SESSION['error'] = "Quantity must be between 1 and 10,000,000.";
    } elseif ($price < 0.01 || $price > 1e21) {
        $_SESSION['error'] = "Buy Price must be between 0.01 and 1,000,000,000,000,000,000,000.";
    } elseif ($selling_price < 0.01 || $selling_price > 1e21) {
        $_SESSION['error'] = "Selling Price must be between 0.01 and 1,000,000,000,000,000,000,000.";
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, quantity, price, selling_price, supplier_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW());");
        $stmt->bind_param("ssiddi", $product_name, $category, $quantity, $price, $selling_price, $supplier_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Item added successfully.";
            header('Location: ../../inventory.php');
            exit;
        } else {
            $_SESSION['error'] = "Error adding item: " . $conn->error;
        }
    }
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Add Item</h1>
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
                    <label for="product_name" class="form-label">Product Name<span class="text-danger">*</span></label>
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
                    <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="10000000" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Buy Price (LKR)</label>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars(number_format(floatval($price), 2)); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="selling_price" class="form-label">Selling Price (LKR)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="selling_price" name="selling_price" value="<?php echo htmlspecialchars(number_format(floatval($selling_price), 2)); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">Select Supplier</option>
                        <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                            <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo $supplier['supplier_id'] == $supplier_id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['supplier_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Product</button>
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
