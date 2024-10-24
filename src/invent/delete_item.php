<?php
include __DIR__ . '/../db_conn.php';

// Check if item ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete item
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?;");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting product: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "No item ID provided.";
}

header('Location: ../../inventory.php');
exit;
?>
