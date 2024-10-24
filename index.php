<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
include 'src/db_conn.php';

if (isset($_SESSION['email'])) {
    echo 'Already logged in as ' . $_SESSION['email'];
    header('Location: inventory.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?;");
    if (!$stmt) {
        echo "\nMySQLi::errorInfo():\n";
        print_r($conn->error_list);
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Check if the account is blocked
        if ($user['blocked_until'] && strtotime($user['blocked_until']) > time()) {
            $_SESSION['error'] = "Your account is locked due to multiple failed login attempts. Please try again after " . date('H:i', strtotime($user['blocked_until'])) . ".";
        } else {
            // Verify password
            if ($user['password'] === $password) {
                // Reset login attempts
                $stmt = $conn->prepare("UPDATE users SET login_attempts = 0, blocked_until = NULL WHERE email = ?;");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                // Set session variables
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = ucfirst($user['first_name']);;

               

                // Redirect to the user dashboard
                header('Location: inventory.php');
                exit;
            } else {
                // Increment login attempts
                $login_attempts = $user['login_attempts'] + 1;

                if ($login_attempts >= 3) {
                    // Block the account for 15 minutes
                    $blocked_until = date("Y-m-d H:i:s", strtotime("+15 minutes"));
                    $stmt = $conn->prepare("UPDATE users SET login_attempts = ?, blocked_until = ? WHERE email = ?;");
                    $stmt->bind_param("iss", $login_attempts, $blocked_until, $email);
                    $stmt->execute();
                    $_SESSION['error'] = "Too many failed login attempts. Your account is locked for 15 minutes.";
                } else {
                    // Update login attempts
                    $stmt = $conn->prepare("UPDATE users SET login_attempts = ? WHERE email = ?;");
                    $stmt->bind_param("is", $login_attempts, $email);
                    $stmt->execute();
                    $_SESSION['error'] = "The email or password you entered is incorrect! Attempt $login_attempts of 3.";
                }
            }
        }
    } else {
        $_SESSION['error'] = "No account found with that email address.";
    }
}
?>

<div class="container-fluid vh-100">
    <div class="row h-100">
        <!-- Left Image Section -->
        <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center p-3"
            style="background-color: #f3f4f6;">
            <img src="dist/images/login.jpg" alt="Bookshop Management System" class="img-fluid rounded"
                style="max-width: 100%; height: auto;">
            <!-- <div class="text-center mt-3">
                <h2>Welcome to ABC Bookshop Management System</h2>
            </div> -->
        </div>

        <!-- Right Form Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-3">
            <div class="w-100 w-md-75 w-lg-50">
            <div class="text-center mt-3">
                <h2>Welcome to ABC Bookshop Management System</h2>
            </div>
                <h1 class="text-center mb-4">Login</h1>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value=''
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            value=''
                            required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>