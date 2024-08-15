<?php
session_start();

if (isset($_POST['place_order'])) {
    if (isset($_SESSION['products']) && count($_SESSION['products']) > 0) {
        include_once __DIR__ . '/../db_conn.php';

        // Create record in order table and get its ID
        $grand_total = isset($_POST['grand_total']) ? $_POST['grand_total'] : 0;

        $orderCreateSql = "INSERT INTO orders (order_total) VALUES ($grand_total)";

        $orderCreated = false;
        $createdOrderId = false;

        if ($conn->query($orderCreateSql) === TRUE) {
            $orderCreated = true;
            $createdOrderId = $conn->insert_id;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unable to create order. Please try again']);
        }

        if ($orderCreated && $createdOrderId) {
            // iterate through the order items and add them to order items table.
            $cartProducts = $_SESSION['products'];

            $orderItemsCreateSql = "INSERT INTO order_items (order_id, product_id, product_price, qty, row_total) VALUES ";
            $orderItemsValuesArray = [];
            foreach($cartProducts as $cartProduct) {
                $rowTotal = $cartProduct['qty'] * $cartProduct['product_price'];

                $orderItemsValuesArray[] = "($createdOrderId, "
                    . $cartProduct['product_id'] . ", "
                    . $cartProduct['product_price'] . ", "
                    . $cartProduct['qty'] . ", "
                    . $rowTotal
                    . ")";
            }

            $orderItemsCreateSql .= implode(", ", $orderItemsValuesArray);

            // Execute the query
            if ($conn->query($orderItemsCreateSql) === TRUE) {
                // Clear products in session
                unset($_SESSION['products']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Unable to create order items for order id ' . $createdOrderId]);
            }
        }

        $conn->close();

        echo json_encode(['status' => 'success', 'message' => 'Order created successfully.', 'orderId' => $createdOrderId]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty!']);
    }
}
