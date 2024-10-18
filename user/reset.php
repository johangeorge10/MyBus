<?php
if (isset($_POST['reset_password'])) {
    // Hash the new password using MD5 (insecure; consider using password_hash() instead)
    $new_password = md5($_POST['new_password']);
    $token = $_POST['token'];

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "mydb");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the token is valid and not expired
    $quer = "SELECT * FROM accinfo WHERE reset_token='$token' AND reset_expires >= '".date("U")."'";
    $sql_result = mysqli_query($conn, $quer);
    $row_count = mysqli_num_rows($sql_result);

    if ($row_count > 0) {
        // Update the password
        $sql = "UPDATE accinfo SET password='$new_password', reset_token=NULL, reset_expires=NULL WHERE reset_token='$token'";
        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Password has been reset. You can now log in with your new password.");window.open("login.php","_self")</script>';
        } else {
            echo '<script>alert("Error updating password.");window.open("reset.php","_self")</script>';
        }
    } else {
        echo '<script>alert("Invalid or expired token.");window.open("forgot.php","_self")</script>';
    }

    // Close the database connection
    $conn->close();
}
?>

<!-- Reset Password Form -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container-fluid">
        <div class="form login">
            <div class="form-content">
                <header>Reset Password</header>
                <form action="reset.php" method="post">
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                    <div class="field input-field">
                        <input type="password" placeholder="New Password" class="password" name="new_password" required>
                    </div>
                    <div class="field-button">
                        <button type="submit" name="reset_password" class="field-button">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
