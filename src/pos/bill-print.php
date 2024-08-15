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
    <link rel="stylesheet" href="/dist/css/bootstrap.min.css">
    <script src="/dist/js/bootstrap.bundle.min.js"></script>
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
        $orderSql = 'SELECT * FROM orders oo 
            LEFT JOIN order_items oi ON oo.id = oi.order_id 
            LEFT JOIN products op ON op.id = oi.product_id
            WHERE oo.id = ' . $orderId ;

        $orderItemsResult = $conn->query($orderSql);

        if ($orderItemsResult->num_rows > 0) { ?>
            <?php
                $orderInfo = [];
                $orderItemsInfo = [];
                $counter = 1;

                if ($orderItemsResult->num_rows > 0) {
                    while($row = $orderItemsResult->fetch_assoc()) {
                        if ($counter == 1) {
                            $orderInfo = [
                                'order_id' => str_pad($row['oo.id'], 8, '0', STR_PAD_LEFT),
                                'order_total' => $row['oo.order_total'],
                            ];
                        }

                        $orderItemsInfo[] = [
                            'oi_qty' => $row['oi.qty'],
                            'oi_price' => $row['oi.product_price'],
                            'oi_row_total' => $row['oi.row_total'],
                            'product_name' => $row['op.product_name'],
                        ];

                        $counter++;
                    }
                }
            ?>

            <div class="content">
                <div>
                    <h1>Bill for order : <?php echo $orderInfo['order_id']; ?></h1>
                    <p>Company Name</p>
                    <p>Address:</p>
                    <p>Date:</p>
                    <p>Time:</p>
                    <p>Phone no:</p>
                </div>
                <div>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                        <?php foreach ($orderItemsInfo as $oiK => $orderItemInfo) { ?>
                            <tr>
                                <td><?php echo $oiK; ?></td>
                                <td><?php echo $orderItemInfo['product_name']; ?></td>
                                <td><?php echo $orderItemInfo['oi_price']; ?></td>
                                <td><?php echo $orderItemInfo['oi_qty']; ?></td>
                                <td><?php echo $orderItemInfo['oi_row_total']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php

            ?>

            <!-- Trigger the print dialog -->
            <!--<script>-->
            <!--    window.onload = function() {-->
            <!--        window.print();-->
            <!--    };-->
            <!--</script>-->

        <?php } else {
            echo 'Order details not found on the system.';
        }

        $conn->close();
    ?>
</body>
</html>