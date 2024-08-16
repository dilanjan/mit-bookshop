<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
include 'src/db_conn.php';

if (isset($_SESSION['username'])) {
    echo 'Already logged in ' . $_SESSION['username'];
   // exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Authenticate user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?;");
    if (!$stmt) {
        echo "\nMySQLi::errorInfo():\n";
        print_r($conn->error_list);
        exit();
    }
    $stmt->bind_param("ss", $_POST['username'], md5($_POST['password']));
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If user exists
    if ($user) {
        $_SESSION['username'] = $user['id'];

        // Handle Remember Me functionality
        if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on') {
            setcookie('username', $_POST['username'], time() + (86400 * 30), "/"); // 30 days
            setcookie('password', md5($_POST['password']), time() + (86400 * 30), "/"); // 30 days
        } else {
            setcookie('username', '', time() - 3600, "/");
            setcookie('password', '', time() - 3600, "/");
        }

        // Redirect
        header('Location: src/user/view_users.php');
        exit;
    } else {
        $_SESSION['error'] = "The username or password you entered is incorrect!";
    }
}
?>

<div class="container-fluid vh-100">
    <div class="row h-100">
        <!-- Left Image Section -->
        <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center p-3" style="background-color: #f3f4f6;">
            <img src="dist/images/login.jpg" alt="Bookshop Management System" class="img-fluid rounded" style="max-width: 100%; height: auto;">
            <div class="text-center mt-3">
                <h2>Welcome to ABC Bookshop Management System</h2>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-3">
            <div class="w-100 w-md-75 w-lg-50">
                <h1 class="text-center mb-4">Login</h1>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : ''; ?>" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                        <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>
