<?php
// Load the mail configuration from config.php
require __DIR__ . '/../config.php';

// Load PHPMailer files from the extracted directory
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if (isset($_POST['forgot'])) {
    $email = $_POST['email'];

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "mydb");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in the database
    $quer = "SELECT * FROM accinfo WHERE email='$email'";
    $sql_result = mysqli_query($conn, $quer);
    $row_count = mysqli_num_rows($sql_result);

    if ($row_count > 0) {
        // Generate a unique token for the reset link
        $token = bin2hex(random_bytes(50));

        // Set token expiration time (1 hour from now)
        $expires = date("U") + 3600;

        // Store the token and expiration in the database
        $sql = "UPDATE accinfo SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        $conn->query($sql);

        // Create a new PHPMailer instance
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $config["mail"]["username"];  // Use config values
        $mail->Password = $config["mail"]["password"];
        $mail->SMTPSecure = 'tls'; // Encryption
        $mail->Port = 587;

        $mail->setFrom($config["mail"]["username"], 'BUS MANAGEMENT SYSTEM'); // Sender's email
        $mail->addAddress($email); // Add the recipient's email

        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = 'Reset your password';
        $mail->Body = "
        Hi,<br><br>
        We received a request to reset your password. Click the link below to reset your password:<br>
        <a href='http://localhost/MyBus/user/reset.php?token=$token'>Reset Password</a><br><br>
        This link will expire in 1 hour.
        ";

        // Send the email
        if ($mail->send()) {
            echo '<script>alert("Password reset link has been sent to your email.");window.open("login.php","_self")</script>';
        } else {
            echo '<script>alert("Failed to send reset email.");window.open("forgot.php","_self")</script>';
        }
    } else {
        echo '<script>alert("Email not found.");window.open("forgot.php","_self")</script>';
    }

    // Close the database connection
    $conn->close();
}
?>

<!-- Forgot Password Form -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container-fluid">
        <div class="form login">
            <div class="form-content">
                <header>Forgot Password</header>
                <form action="forgot.php" method="post">
                    <div class="field input-field">
                        <input type="email" placeholder="Email" class="email" name="email" required>
                    </div>
                    <div class="field-button">
                        <button type="submit" name="forgot" class="field-button">Send Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
