<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../../index.php');
    exit;
}

// Fetch item orders
$stmt = $conn->prepare("SELECT ori.order_id, i.product_name, 
 ori.qty, ori.row_total FROM order_items ori JOIN products i ON ori.product_id = i.id ;");
$stmt->execute();
$order_items = $stmt->get_result();
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
                        <th>Quantity Ordered</th>
                        <th>Total Cost</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $order_items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['qty']); ?></td>
                            <td><?php echo htmlspecialchars($order['row_total']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
