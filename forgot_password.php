<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
include 'src/db_conn.php';
require 'vendor/autoload.php'; // Include PHPMailer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?;");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $resetToken = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?;");
        $stmt->bind_param("ss", $resetToken, $email);
        $stmt->execute();

        $resetLink = "http://localhost/mit-bookshop/reset_password.php?token=$resetToken";

        // Send the reset link via email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;             // Enable SMTP authentication
            $mail->Username   = 'your-email@gmail.com'; // SMTP username
            $mail->Password   = 'your-email-password';  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS encryption
            $mail->Port       = 587;              // TCP port to connect to

            //Recipients
            $mail->setFrom('your-email@gmail.com', 'Bookshop Management System');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = 'To reset your password, please click the following link: <a href="' . $resetLink . '">Reset Password</a>';

            $mail->send();
            $_SESSION['success'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "No account found with that email address.";
    }
}
?>

<div class="container vh-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Forgot Password</h1>
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
                    <label for="email" class="form-label">Enter your email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/src/templates/common/footer.php'; ?>
