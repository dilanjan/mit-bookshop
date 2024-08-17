<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';


// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    $_SESSION['error'] = 'You do not have permission to add users.';
    header('Location: view_users.php');
    exit;
}

// Initialize variables with user data if available
$email = '';
$first_name = '';
$last_name = '';
$role = '';

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
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $role = $user['role'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get POST data
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $role = $_POST['role'];

        // Update user in the database
        $stmt = $conn->prepare("UPDATE users SET email=?, first_name=?, last_name=?, role=? WHERE user_id=?;");
        $stmt->bind_param("ssssi", $email, $first_name, $last_name, $role, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "User updated successfully.";
            header('Location: view_users.php');
            exit;
        } else {
            $_SESSION['error'] = "Error updating user: " . $conn->error;
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
                        <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="manager" <?php echo $role == 'manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="staff" <?php echo $role == 'staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">

                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>