<?php
include __DIR__ . '/../db_conn.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?;");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting user: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "No user ID provided.";
}

header('Location: view_users.php');
exit;
?>
