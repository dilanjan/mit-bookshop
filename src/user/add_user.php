<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    $_SESSION['error'] = 'You do not have permission to add users.';
    header('Location: view_users.php');
    exit;
}

// Initialize variables with empty values
$email = '';
$password = '';
$confirm_password = '';
$first_name = '';
$last_name = '';
$role = '';

// Check if there was a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];

    // Validate password
    $password_error = '';
    if ($password !== $confirm_password) {
        $password_error = 'Passwords do not match!';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $password_error = 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.';
    }

    if (empty($password_error)) {
        $hashed_password = md5($password);
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?);");
        $stmt->bind_param("sssss", $email, $hashed_password, $first_name, $last_name, $role);
        if ($stmt->execute()) {
            $_SESSION['success'] = "User added successfully.";
            header('Location: view_users.php');
            exit;
        } else {
            $_SESSION['error'] = "Error adding user: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = $password_error;
    }
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Add User</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        value="<?php echo htmlspecialchars($password); ?>" required>
                    <div class="form-text">Password must be at least 8 characters long, include at least one uppercase
                        letter, one lowercase letter, one number, and one special character.</div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        value="<?php echo htmlspecialchars($confirm_password); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="<?php echo htmlspecialchars($first_name); ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="manager" <?php echo $role === 'manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="staff" <?php echo $role === 'staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">

                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>