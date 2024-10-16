<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Output script to clear sessionStorage on the client side and redirect to login
echo '
<script>
    // Clear sessionStorage
    sessionStorage.clear();
    
    // Redirect to login page
    window.location.href = "login.php";
</script>
';
?>
