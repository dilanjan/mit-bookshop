<?php
session_start();

if (isset($_POST['del_confirm']) && isset($_POST['product_id'])) {
    if (isset($_SESSION['products']) && count($_SESSION['products']) > 0) {
        $extCiIndex = false;

        foreach ($_SESSION['products'] as $pk => $product) {
            if ($product['product_id'] == $_POST['product_id']) {
                $extCiIndex = $pk;
            }
        }

        if ($extCiIndex !== false) {
            unset($_SESSION['products'][$extCiIndex]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Product has been removed from cart!', 'cart_items' => $_SESSION['products']]);
    }
}
