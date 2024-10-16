<?php
if (isset($_POST['login'])) {
    session_start(); // Start or resume the session

    // Get the email and password from the login form
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Basic validation for empty fields
    if (empty($email) || empty($pass)) {
        echo '<script>alert("Email and Password cannot be empty");window.open("login.php","_self")</script>';
        die();
    }

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "mydb");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch user information
    $quer = "SELECT * FROM accinfo WHERE email='$email'";
    $sql_result = mysqli_query($conn, $quer);
    $row_count = mysqli_num_rows($sql_result);

    if ($row_count > 0) {
        // Fetch the hashed password from the database
        $quer = "SELECT password, type FROM accinfo WHERE email='$email'";
        $sql_result = mysqli_query($conn, $quer);
        $result = $sql_result->fetch_assoc();

        // Verify the password (assuming passwords are stored as MD5 hashes)
        if ($result['password'] == md5($pass)) {
            // Set the email in session after successful login
            $_SESSION['email'] = $email;

            // Check if the user is an admin or regular user
            if ($result['type'] == 'u') {
                echo '<script>window.open("../home/newhome.php","_self")</script>';
            } else {
                echo '<script>window.open("../admin/admindash.php","_self")</script>';
            }
        } else {
            // If the password doesn't match
            echo '<script>alert("Incorrect password. Please try again.");window.open("login.php","_self")</script>';
        }
    } else {
        // If the email does not exist
        echo '<script>alert("Email does not exist. Please sign up.");window.open("login.php","_self")</script>';
    }

    // Close the database connection
    $conn->close();
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="login.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="form login">
<div class="form-content">
    <header>Login</header>
<form action="login.php" method="post">
    <div class="field input-field">
        <input type="email" placeholder="Email" class="email" name="email" required>
        </div>
        <div class="field input-field">

<input type="password" placeholder="password" class="password" name="pass"required>
    </div>
    <div class="form-link">
        <a href="forgot.php">Forgot password</a>
 </div>
    <div class="field-button">
    <button type="submit" name="login" class="field-button" value="login">Login</button>
    </div>
</form>
<div class="form-link">
    <span>Don't have an account?<a href="signup.php" class="signup-link">Sign up</a></span>
 </div>
</div>
</div>
</div>
</body>
</html>
