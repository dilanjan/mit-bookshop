<?php
session_start();

include_once __DIR__ . '/../pageChecker.php';
checkUserLogged();

if (!isset($_GET['o_id'])) {
    header('Location: /pos.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/bootstrap.min.css">
    <script src="<?php echo BASE_URL; ?>dist/js/bootstrap.bundle.min.js"></script>
    <title>BookshoPOS</title>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            /* Customize the print view */
        }
    </style>
</head>
<body>
    <?php
        include_once __DIR__ . '/../db_conn.php';

        // Get order and its order items to print the bill
        $orderId = $_GET['o_id'];
        $orderSql = 'SELECT oo.id as oo_id, oo.order_total, oi.id as oi_id, oi.product_price as order_item_price, oi.qty, oi.row_total, op.product_name FROM orders oo 
            LEFT JOIN order_items oi ON oo.id = oi.order_id 
            LEFT JOIN products op ON op.id = oi.product_id
            WHERE oo.id = ' . $orderId ;

        $orderItemsResult = $conn->query($orderSql);

        if ($orderItemsResult->num_rows > 0) { ?>
            <?php
                $orderInfo = [];
                $orderItemsInfo = [];
                $counter = 1;

                while($row = $orderItemsResult->fetch_assoc()) {
                    if ($counter == 1) {
                        $orderInfo = [
                            'order_id' => str_pad($row['oo_id'], 8, '0', STR_PAD_LEFT),
                            'order_total' => $row['order_total'],
                        ];
                    }

                    $orderItemsInfo[] = [
                        'oi_qty' => $row['qty'],
                        'oi_price' => $row['order_item_price'],
                        'oi_row_total' => $row['row_total'],
                        'product_name' => $row['product_name'],
                    ];

                    $counter++;
                }
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-12 text-center my-5">
                        <p class="mb-3 fs-3 fw-bold">Edu1st Bookshop &amp; Publishers</p>
                        <p class="mb-1">No 100, 2nd Cross Street,<br/>
                            Colombo 10, 01000.</p>
                        <p class="mb-1">Phone no: +94 777 122 866</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <span class="fw-bold">Order : </span><span class="fs-4"><?php echo $orderInfo['order_id']; ?></span>
                    </div>
                    <div class="col-6 mt-3">
                        <span class="fw-bold">Date : </span>
                    </div>
                    <div class="col-6 mt-3">
                        <span class="fw-bold">Time : </span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="row border-bottom py-2 mb-3">
                            <div class="col-1 fs-5">#</div>
                            <div class="col-5 fs-5">Product</div>
                            <div class="col-2 fs-5 text-end">Item Price</div>
                            <div class="col-2 fs-5 text-end">Qty</div>
                            <div class="col-2 fs-5 text-end">Total</div>
                        </div>
                        <?php foreach ($orderItemsInfo as $oiK => $orderItemInfo) { ?>
                            <div class="row py-1">
                                <div class="col-1"><?php echo $oiK + 1; ?></div>
                                <div class="col-5"><?php echo $orderItemInfo['product_name']; ?></div>
                                <div class="col-2 text-end">Rs. <?php echo $orderItemInfo['oi_price']; ?></div>
                                <div class="col-2 text-end"><?php echo $orderItemInfo['oi_qty']; ?></div>
                                <div class="col-2 text-end"><?php echo $orderItemInfo['oi_row_total']; ?></div>
                            </div>
                        <?php } ?>
                        <div class="row mt-4 py-2 border-top border-bottom border-2">
                            <div class="col-1 fs-5"></div>
                            <div class="col-5 fs-5"></div>
                            <div class="col-2 fs-5 text-end"> </div>
                            <div class="col-2 fs-5 fw-bold text-end">Grand Total</div>
                            <div class="col-2 fs-5 fw-bold text-end"><?php echo $orderInfo['order_total']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Trigger the print dialog -->
            <script>
                window.onload = function() {
                    window.print();
                };
            </script>

        <?php } else {
            echo 'Order details not found on the system.';
        }

        $conn->close();
    ?>
</body>
</html>