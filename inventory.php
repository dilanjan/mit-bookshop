<?php include __DIR__ . '/src/templates/common/header.php'; ?>
<?php
include __DIR__ . '/src/db_conn.php';


// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../../index.php');
    exit;
}

// Fetch search term if present
$search_term = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Fetch sorting options if present, default to quantity and ascending order
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'quantity';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Validate and sanitize sorting options
$valid_columns = ['product_name', 'category', 'quantity', 'price', 'selling_price', 'supplier_name', 'created_at', 'updated_at'];
if (!in_array($sort_column, $valid_columns)) {
    $sort_column = 'quantity'; // Default sort column
}
if (!in_array(strtoupper($sort_order), ['ASC', 'DESC'])) {
    $sort_order = 'ASC'; // Default sort order
}

// Pagination settings
$products_per_page = 5;
$total_items_stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM products 
    JOIN suppliers ON products.supplier_id = suppliers.supplier_id 
    WHERE product_name LIKE ? OR category LIKE ? OR supplier_name LIKE ?
");
$total_items_stmt->bind_param("sss", $search_term, $search_term, $search_term);
$total_items_stmt->execute();
$total_items = $total_items_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_items / $products_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

$stmt = $conn->prepare("
    SELECT products.id, products.product_name, products.category, products.quantity, products.price, products.selling_price, suppliers.supplier_name, products.created_at, products.updated_at
    FROM products
    JOIN suppliers ON products.supplier_id = suppliers.supplier_id
    WHERE product_name LIKE ? OR category LIKE ? OR supplier_name LIKE ?
    ORDER BY $sort_column $sort_order
    LIMIT ?, ?
");

// Bind parameters, excluding the LIMIT clause parameters
$stmt->bind_param("sssii", $search_term, $search_term, $search_term, $offset, $products_per_page);
$stmt->execute();
$products = $stmt->get_result();

// Role checking function for delete permissions
function can_delete($role) {
    return in_array($role, ['admin', 'manager']);
}

// Get user's role
$user_role = $_SESSION['role'];
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center mb-4">Inventory</h1>
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
            <a href="http://localhost/mit-bookshop/src/invent/add_item.php" class="btn btn-primary mb-3">Add Item</a>
            <a href="http://localhost/mit-bookshop/src/invent/item_orders_report.php" class="btn btn-primary mb-3">Report</a>
            
            <!-- Search and Filter Options -->
            <form class="mb-3" method="GET" action="">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by product name, category, or supplier" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><a href="?sort=product_name&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Item Name</a></th>
                        <th><a href="?sort=category&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Category</a></th>
                        <th><a href="?sort=quantity&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Quantity</a></th>
                        <th><a href="?sort=price&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Buy Price (LKR)</a></th>
                        <th><a href="?sort=selling_price&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Selling Price (LKR)</a></th>
                        <th><a href="?sort=supplier_name&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Supplier</a></th>
                        <th><a href="?sort=created_at&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Created At</a></th>
                        <th><a href="?sort=updated_at&order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&page=<?php echo $current_page; ?>">Updated At</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <tr <?php if ($product['quantity'] < 5) echo 'class="table-danger"'; ?>>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo number_format($product['quantity']); ?></td>
                            <td><?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo number_format($product['selling_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($product['updated_at']); ?></td>
                            <td>
                                <a href="http://localhost/mit-bookshop/src/invent/edit_item.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <?php if (can_delete($user_role)): ?>
                                    <a href="http://localhost/mit-bookshop/src/invent/delete_item.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>
