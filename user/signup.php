<?php
if (isset($_POST['signup'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $repass = $_POST['pass'];
    if ($pass != $repass) {
        echo "password and retype password must be same";
        die;
    }

    $conn = new mysqli("localhost", "root", "", "mydb");
    $quer = "select * from accinfo where email='$email'";
    $sql_result = mysqli_query($conn, $quer);
    $row = mysqli_num_rows($sql_result);
    if ($row > 0) {
        echo '<script>alert("Account already exist for this email try loggin in");window.open("login.php","_self")</script>';
        die;
    }
    $securepass = md5($pass);
    $query = "insert into accinfo(email,password,type) values('$email','$securepass','u')";

    $sql_status = mysqli_query($conn, $query);

    if ($sql_status) {
        echo '<script>alert("Signup Success redirecting to login page");window.open("login.php","_self")</script>';
    } else {
        echo "signup failed try again";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section class="container-fluid">
        <div class="form login">
<div class="form-content">
    <header>SignUp</header>
<form action="signup.php" method="POST" onsubmit="return validate()">
    <div class="field input-field">
        <input type="email" placeholder="Email" name="email" id="email" required>
        </div>
        <div class="field input-field">

<input type="password" placeholder="password" name="pass" id="password"required>
</div>
<div class="field input-field">
<input type="password" placeholder="Retype password" name="pass1" id="password1"required>
    </div>
    <div class="field-button">
    <button type="submit" name="signup" class="field-button" value="signup">Signup</button>  </div>
   <span>Already Have Account ?<a href="login.php" class="login-red">Login</a></span>
</form>
</div>
</div>
 </section>
</body>
<script>
function validate()
{ var mail=document.getElementById("email").value;
    var pass1=document.getElementById("password").value;
 var pass=document.getElementById("password1").value;
if(pass1==pass)
{
    return true;
}
    else
    {
      alert("passsword and retype password must be same");
return false;
    }
    
}

</script>
</html>