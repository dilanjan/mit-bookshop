<?php include __DIR__ . '/../templates/common/header.php'; ?>
<?php
include __DIR__ . '/../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("ssssss", $username, $password, $email, $first_name, $last_name, $role);
    if ($stmt->execute()) {
        $_SESSION['success'] = "User added successfully.";
        header('Location: view_users.php');
        exit;
    } else {
        $_SESSION['error'] = "Error adding user: " . $conn->error;
    }
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Add User</h1>
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
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add User</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/common/footer.php'; ?>
