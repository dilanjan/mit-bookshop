<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
session_start();

// Perform the logout only if the confirm GET parameter is set to true
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Redirect to login page after logout
    header('Location: index.php');
    exit;
}
?>

<script>
    // Show a confirmation dialog before proceeding with the logout
    if (confirm('Are you sure you want to log out?')) {
        // If the user clicks "OK", redirect to the same page with confirmation
        window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?confirm=true";
    } else {
        window.location.href = "<?php echo BASE_URL; ?>pos.php"; 
    }
</script>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>
