<?php
include __DIR__ . '/../db_conn.php';

// Check if item ID is provided
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Delete item
    $stmt = $conn->prepare("DELETE FROM items WHERE item_id=?;");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Item deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting item: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "No item ID provided.";
}

header('Location: view_items.php');
exit;
?>
