<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['qty']) && isset($_POST['product_name']) && isset($_POST['product_price']) && $_POST['qty'] > 0) {

    if(isset($_SESSION['products']) && count($_SESSION['products']) > 0) {
        $extCiIndex = false;

        foreach ($_SESSION['products'] as $pk => $product) {
            if ($product['product_id'] == $_POST['product_id']) {
                $extCiIndex = $pk;
            }
        }

        if ($extCiIndex !== false) {
            $_SESSION['products'][$extCiIndex] = [
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'qty' => $_POST['qty']
            ];
        } else {
            $_SESSION['products'][] = [
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'qty' => $_POST['qty']
            ];
        }
    } else {
        $_SESSION['products'][] = [
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price'],
            'qty' => $_POST['qty']
        ];
    }

    echo json_encode(['status' => 'success', 'cart_items' => $_SESSION['products']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid request']);
}