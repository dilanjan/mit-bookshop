<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, first_name=?, last_name=?, role=? WHERE user_id=?;");
        $stmt->bind_param("sssssi", $username, $email, $first_name, $last_name, $role, $user_id);
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
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update User</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
