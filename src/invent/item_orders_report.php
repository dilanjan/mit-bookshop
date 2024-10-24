<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../../index.php');
    exit;
}

// Fetch item orders
$stmt = $conn->prepare("SELECT io.order_id, i.product_name, s.supplier_name, io.order_date, io.quantity_ordered, io.total_cost, io.status FROM item_orders io JOIN products i ON io.item_id = i.item_id JOIN suppliers s ON io.supplier_id = s.supplier_id;");
$stmt->execute();
$item_orders = $stmt->get_result();
?>

<div class="container vh-100">
    <div class="row h-100 align-products-center justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Item Orders Report</h1>
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Item Name</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Quantity Ordered</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $item_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity_ordered']); ?></td>
                            <td><?php echo htmlspecialchars($order['total_cost']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
