<?php
include_once __DIR__ . '/db_conn.php';

$result = false;
$totalPages = false;

if (isset($tableName)) {

    $countQuery = 'SELECT COUNT(*) as total from ' . $tableName;

    $countResult = $conn->query($countQuery);
    $countRow = $countResult->fetch_assoc();
    $totalRecords = $countRow['total'];

    $recordPerPage = 10;
    $totalPages = ceil($totalRecords / $recordPerPage);

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $offset = ($page - 1) * $recordPerPage;

    $recordsQuery = "SELECT * FROM $tableName LIMIT $recordPerPage OFFSET $offset";
    $result = $conn->query($recordsQuery);
}