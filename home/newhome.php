<?php
session_start(); // Start the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    // Redirect to login page if the session does not exist
    header("Location: login.php");
    exit();
}
?>
<html>
<head>
    <title>MyBus</title>
    <link rel="stylesheet" href="newhome.css">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Sketch&display=swap" rel="stylesheet">
</head>
<body>
    <div class="banner"> 
        <div class="navbar">
            <img src="./img/logo.png" class="logo"> 
            <p style="color: white; margin: 0; padding: 0; font-family: 'Londrina Sketch', cursive;"> 
            Hello  
            <?php 
            $email_parts = explode('@', $email);  // Split the email at '@'
            echo '         ' . $email_parts[0] . '  !!!';
            ?>
            </p>
            <ul>
                <li><a href="#">HOME</a></li>
                <li><a href="../index/index.php">MAKE BOOKING</a></li>
                <li><a href="../ticket/checkticket.php">CHECK TICKETS</a></li>
                <li><button onclick="logoutUser()">LOGOUT</button></li>
            </ul>
        </div>
        <div class="content">
            <h1>BOOK YOUR TICKET</h1>
            <p>Your Perfect Travel Partner. Now book your Bus Ticket easily with myBus.</p>
            <div>
                <button type="button" onclick="redirectToIndex()"><span ></span>BOOK NOW</button>
            </div>
        </div>
    </div>
 
    <script>
        function redirectToIndex() {
         window.location.href = "../index/index.php";
        }

        // Redirect to logout.php to clear session and session storage
        function logoutUser() {
            window.location.href = "../user/logout.php";
        }
        function isLoggedIn() {
            var isLoggedIn = sessionStorage.getItem('isLoggedIn');
            return isLoggedIn === 'true';
        }

        function displayUserEmail() {
            var userEmail = sessionStorage.getItem('userEmail');
    var userEmailElem = document.getElementById('user-email');
    if (userEmail) {
        userEmailElem.style.display = 'block';
        userEmailElem.textContent = userEmail;
    } else {
        userEmailElem.style.display = 'none';
    }
}

        // Call the function when the page loads
        window.onload = function() {
            displayUserEmail();
        };
    </script>

  
</body>
</html>
