<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
// Start session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Remove cookies if they exist
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, "/");
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, "/");
}

// Redirect to login page
header('Location: login.php');
exit;
?>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>