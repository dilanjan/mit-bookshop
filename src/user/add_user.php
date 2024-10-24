<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['email']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
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

// Initialize error messages
$errors = [];

// Check if there was a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data and sanitize
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $role = $_POST['role'];

    // Validate required fields
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if (empty($confirm_password)) {
        $errors[] = 'Confirm password is required.';
    }

    if (empty($role)) {
        $errors[] = 'Role is required.';
    }

    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }

    // Validate password
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match!';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.';
    }

    // Check if email already exists in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'This email is already registered. Please use a different email.';
        }
    }

    // If there are no errors, proceed with inserting the user into the database
    if (empty($errors)) {
        $hashed_password = md5($password);  // Use password_hash for better security
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $hashed_password, $first_name, $last_name, $role);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "User added successfully.";
            header('Location: view_users.php');
            exit;
        } else {
            $_SESSION['error'] = "Error adding user: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Add User</h1>

            <!-- Display error messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Display success messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Email (Mandatory) -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <!-- Password (Mandatory) -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">Password must be at least 8 characters long, include at least one uppercase
                        letter, one lowercase letter, one number, and one special character.</div>
                </div>

                <!-- Confirm Password (Mandatory) -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <!-- First Name -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="<?php echo htmlspecialchars($first_name); ?>">
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="<?php echo htmlspecialchars($last_name); ?>">
                </div>

                <!-- Role (Mandatory) -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" disabled selected>Select a role</option>
                        <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="manager" <?php echo $role === 'manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="staff" <?php echo $role === 'staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>

                <!-- Submit and Cancel -->
                <div class="d-flex justify-content-between">
                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
