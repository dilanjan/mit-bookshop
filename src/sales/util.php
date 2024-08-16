<?php

function paddedOrderId($orderId) {
    return str_pad($orderId, 8, '0', STR_PAD_LEFT);
}

function formatCurrency($amount) {
    return 'Rs. ' . number_format($amount, 2, '.', '');
}
