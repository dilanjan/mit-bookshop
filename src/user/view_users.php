<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../../login.php');
    exit;
}

// Fetch the logged-in user's role
$user_role = $_SESSION['role'] ?? '';

// Fetch all users from the database
$stmt = $conn->prepare("SELECT user_id, email, first_name, last_name, role FROM users;");
$stmt->execute();
$users = $stmt->get_result();
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">User Management</h1>
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

            <!-- Show Add User button only for Admins and Managers -->
            <?php if ($user_role === 'admin' || $user_role === 'manager'): ?>
                <a href="add_user.php" class="btn btn-primary mb-3">Add User</a>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <?php if ($user_role === 'admin' || $user_role === 'manager'): ?>
                                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                <?php else: ?>
                                    <button class="btn btn-warning btn-sm" disabled>Edit</button>
                                    <button class="btn btn-danger btn-sm" disabled>Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>