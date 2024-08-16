<?php
include_once __DIR__ . '/../db_conn.php';
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/../config.php';

if (!isset($_GET['order_id'])) { ?>
    <p>Invalid order selected.</p>
<?php } else {
    $orderQuery = "SELECT * from orders where id=" . $_GET['order_id'];

    $order = $conn->query($orderQuery);
    $orderRow = $order->fetch_assoc();
    $orderItems = false;

    if (isset($orderRow['id'])) {
        $orderItemsQuery = "SELECT oi.id as oi_id, oi.product_price, oi.qty, oi.row_total, op.product_name from order_items oi "
            . "LEFT JOIN products op ON op.id = oi.product_id "
            . "where order_id=" . $_GET['order_id'];

        $orderItems = $conn->query($orderItemsQuery);
    }
?>

<div class="container">
    <div class="row mt-5">
        <div class="col m-auto">
            <h1>Order: <?php echo paddedOrderId($orderRow['id']); ?></h1>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="col">Order Id</th>
                        <td><?php echo paddedOrderId($orderRow['id']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Created Date</th>
                        <td><?php echo getDateInColTimezone($orderRow['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Created Time</th>
                        <td><?php echo getTimeInColTimezone($orderRow['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Order Total</th>
                        <td><?php echo formatCurrency($orderRow['order_total']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($orderItems->num_rows > 0) { ?>
        <div class="row mt-5">
            <div class="col-12">
                <p class="fs-4">Order Items</p>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Item ID</th>
                        <th scope="col">Product name</th>
                        <th scope="col" class="text-end">Item price</th>
                        <th scope="col" class="text-end">Quantity</th>
                        <th scope="col" class="text-end">Row total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($orderItemsRows = $orderItems->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $orderItemsRows['oi_id']; ?></td>
                            <td><?php echo $orderItemsRows['product_name']; ?></td>
                            <td class="text-end"><?php echo formatCurrency($orderItemsRows['product_price']); ?></td>
                            <td class="text-end"><?php echo $orderItemsRows['qty']; ?></td>
                            <td class="text-end"><?php echo formatCurrency($orderItemsRows['row_total']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>