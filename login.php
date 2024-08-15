<?php
session_start(); // Start session to manage user login state
include 'src/templates/common/header.php'; // Include header template

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming you have a function to validate the user credentials
    include 'src/db_conn.php';
    include 'user.php';

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->authenticate($username, $password)) {
        // Set session variables
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<div class="container">
    <div class="row vh-100">
        <div class="col-md-4 m-auto">
            <h1 class="text-center">Login</h1>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</div>

<?php include 'src/templates/common/footer.php'; // Include footer template ?>
