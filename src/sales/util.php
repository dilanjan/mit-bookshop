<?php

function paddedOrderId($orderId) {
    return str_pad($orderId, 8, '0', STR_PAD_LEFT);
}

function formatCurrency($amount) {
    return 'Rs. ' . number_format($amount, 2, '.', '');
}

function getDateInColTimezone($dateString) {
    $dtObj = new DateTime($dateString, new DateTimeZone('UTC'));
    $dtObj->setTimezone(new DateTimeZone('Asia/Colombo'));
    return $dtObj->format('Y-m-d');
}


function getTimeInColTimezone($dateString) {
    $dtObj = new DateTime($dateString, new DateTimeZone('UTC'));
    $dtObj->setTimezone(new DateTimeZone('Asia/Colombo'));
    return $dtObj->format('H:i:s');
}

function getDateTimeInColTimezone($dateString) {
    $dtObj = new DateTime($dateString, new DateTimeZone('UTC'));
    $dtObj->setTimezone(new DateTimeZone('Asia/Colombo'));
    return $dtObj->format('Y-m-d H:i:s');
}
