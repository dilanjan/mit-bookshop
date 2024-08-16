<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
include 'src/db_conn.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token is valid
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND reset_token_expiry > NOW();");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = md5($_POST['password']);
            $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE reset_token=?;");
            $stmt->bind_param("ss", $newPassword, $token);
            $stmt->execute();

            $_SESSION['success'] = "Your password has been updated successfully.";
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid or expired reset token.";
    }
} else {
    $_SESSION['error'] = "No reset token provided.";
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Reset Password</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Password</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>
