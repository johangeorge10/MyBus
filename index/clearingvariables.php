<?php
session_start(); // Start the session

// Store the email in a temporary variable
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// If the email was set, restart the session and restore the email
if ($email) {
    session_start(); // Start a new session
    $_SESSION['email'] = $email; // Restore the email
    $_SESSION['name'] = $name;
}

// Optionally, you can return a success message or status
echo json_encode(['status' => 'success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Variables</title>
</head>
<body>
    <script>
      // Function to clear all session storage items except a specified key
      function clearSessionStorageExcept(keyToKeep) {
        for (let key in sessionStorage) {
          if (key !== keyToKeep) {
            sessionStorage.removeItem(key);
          }
        }
      }

      // Execute the following code when the page loads
      document.addEventListener('DOMContentLoaded', function() {
        // Clear session storage except 'isLoggedIn'
        clearSessionStorageExcept('isLoggedIn');

        // Redirect to home after clearing session variables
        window.location.href = "../home/newhome.php";
      });
    </script>
</body>
</html>
