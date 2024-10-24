<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['email']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    $_SESSION['error'] = 'You do not have permission to edit users.';
    header('Location: view_users.php');
    exit;
}

// Initialize variables with user data if available
$email = '';
$first_name = '';
$last_name = '';
$role = '';
$password = '';
$confirm_password = '';

// Check if there was an ID in the query parameters
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?;");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header('Location: view_users.php');
        exit;
    }

    // Prepopulate variables with user data
    $email = $user['email'];
    $first_name = ucfirst($user['first_name']);;
    $last_name = ucfirst($user['last_name']);;
    $role = $user['role'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get POST data
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $role = $_POST['role'];
        
        $reset_password = isset($_POST['reset_password']); 

        if ($reset_password) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
        }

        // Validate required fields
        if (empty($email) || empty($first_name) || empty($role)) {
            $_SESSION['error'] = "Please fill in all required fields.";
        } elseif ($reset_password && (empty($password) || empty($confirm_password))) {
            $_SESSION['error'] = "Please fill in the password fields.";
        } elseif ($reset_password && ($password !== $confirm_password)) {
            $_SESSION['error'] = "Passwords do not match.";
        } elseif ($reset_password && (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password))) {
            $_SESSION['error'] = "Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
        } else {
            // Update user in the database
            $stmt = $conn->prepare("UPDATE users SET email=?, first_name=?, last_name=?, role=? WHERE user_id=?;");
            $stmt->bind_param("ssssi", $email, $first_name, $last_name, $role, $user_id);
            if ($stmt->execute()) {
                // Update password if provided
                if ($reset_password && !empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id=?;");
                    $stmt->bind_param("si", $hashed_password, $user_id);
                    $stmt->execute();
                }
                $_SESSION['success'] = "User updated successfully.";
                header('Location: view_users.php');
                exit;
            } else {
                $_SESSION['error'] = "Error updating user: " . $conn->error;
            }
        }
    }
} else {
    $_SESSION['error'] = "No user ID provided.";
    header('Location: view_users.php');
    exit;
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Edit User</h1>
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
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name </label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="manager" <?php echo $role == 'manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="staff" <?php echo $role == 'staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                
                <!-- Advance Edit Section -->
                <div class="mb-3">
                    <input type="checkbox" id="reset_password" name="reset_password">
                    <label for="reset_password" class="form-label">Reset Password</label>
                </div>
                <div id="advanced-section" style="display: none;">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text">Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('reset_password').onclick = function() {
        var advancedSection = document.getElementById('advanced-section');
        advancedSection.style.display = this.checked ? 'block' : 'none';
        
        var passwordInput = document.getElementById('password');
        var confirmPasswordInput = document.getElementById('confirm_password');
        
        // Toggle required attribute based on visibility
        if (advancedSection.style.display === 'block') {
            passwordInput.setAttribute('required', 'required');
            confirmPasswordInput.setAttribute('required', 'required');
        } else {
            passwordInput.removeAttribute('required');
            confirmPasswordInput.removeAttribute('required');
        }
    };
</script>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
