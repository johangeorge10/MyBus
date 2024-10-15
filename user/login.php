<?php
if (isset($_POST['login'])) {
    session_start();
    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    $pass = $_POST['pass'];
    if ($pass == "" || $email == "") {
        echo "No fields cannot be Empty";
        die;
    }

    $conn = new mysqli("localhost", "root", "", "mydb");
    $quer = "select * from accinfo where email='$email'";
    $sql_result = mysqli_query($conn, $quer);
    $row = mysqli_num_rows($sql_result);
    if ($row > 0) {
        $quer = "select password from accinfo where email='$email'";
        $sql_result = mysqli_query($conn, $quer);
        $resultstring = $sql_result->fetch_row();
        if ($resultstring[0] == md5($pass)) {
            $query = "select type from accinfo where email='$email'";
            $sql_result = mysqli_query($conn, $query);
            $resultstring1 = $sql_result->fetch_row()[0];

            if ($resultstring1=="u") {
                $_SESSION['email'] = $email;
                echo '<script>alert("Login Success");window.open("../home/newhome.html","_self")</script';
            } else {
                $_SESSION['email'] = $email; // Store the email in a session variable
                echo '<script>window.open("../admin/admindash.php","_self")</script>';

            }
        } else {
            echo '<script>alert("Credentials dont match");window.open("login.php","_Self")</script>';
        }
    } else {
        echo '<script>alert("Email does not have an account");window.open("login.php","_Self")</script>';
    }
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
