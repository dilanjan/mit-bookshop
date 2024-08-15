<?php
include_once __DIR__ . '/../db_conn.php';

$productsQuery = "Select * from products";

if (isset($_GET['query'])) {
    $productsQuery .= " where product_name like '%" . $_GET['query'] . "%'";
}

$products = $conn->query($productsQuery);

$productData = [];

if ($products->num_rows > 0) {
    while($row = $products->fetch_assoc()) {
        $productData[] = [
            'id' => $row['id'],
            'product_name' => $row['product_name'],
            'product_price' => $row['price'],
        ];
    }
}

$conn->close();

echo json_encode($productData);