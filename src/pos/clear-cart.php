<?php
session_start();

if (isset($_POST['del_confirm'])) {
    if (isset($_SESSION['products']) && count($_SESSION['products']) > 0) {
        unset($_SESSION['products']);

        echo json_encode(['status' => 'success', 'message' => 'Products has been removed from cart!']);
    }
}
