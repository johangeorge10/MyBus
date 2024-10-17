<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the parameters from the URL
$seatnumbers = isset($_GET['seatnumbers']) ? explode(',', urldecode($_GET['seatnumbers'])) : [];
$date = isset($_GET['date']) ? urldecode($_GET['date']) : '';
$busid = isset($_GET['busid']) ? urldecode($_GET['busid']) : '';
$arrtime = isset($_GET['arrtime']) ? urldecode($_GET['arrtime']) : '';

// Define the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the booking details based on busid, date, arrtime, and seatnumbers
$seatnumbersPlaceholder = implode(',', array_fill(0, count($seatnumbers), '?'));
$query = "SELECT totalamount FROM booked WHERE busid = ? AND date = ? AND arrtime = ? AND seatnumber IN ($seatnumbersPlaceholder)";
$stmt = $conn->prepare($query);
$params = array_merge([$busid, $date, $arrtime], $seatnumbers);
$stmt->bind_param(str_repeat('s', count($params)), ...$params); // Ensure proper binding types
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No bookings found for the provided details.";
    exit();
}

$booking = $result->fetch_assoc();

// Calculate the current time and trip time
$tripDateTime = new DateTime($date . ' ' . $arrtime);
$currentDateTime = new DateTime();
$interval = $currentDateTime->diff($tripDateTime);
$hoursRemaining = $interval->h + ($interval->days * 24);

if ($hoursRemaining < 1) {
    echo "Refunds are not allowed within 1 hour of the trip.";
    exit();
}

// Calculate the refundable amount (90% of the total)
$refundableAmount = $booking['totalamount'] * 0.9;

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each seat number and delete the corresponding booking
    foreach ($seatnumbers as $seatnumber) {
        $deleteQuery = "DELETE FROM booked WHERE busid = ? AND date = ? AND arrtime = ? AND seatnumber = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('issi', $busid, $date, $arrtime, $seatnumber);
        $deleteStmt->execute();
    }

    // Display refund confirmation
    echo "<h3>Your refund of ₹" . number_format($refundableAmount, 2) . " has been processed. Thank you!</h3>";
    echo "<a href='../ticket/checkticket.php' class='btn btn-primary'>Exit</a>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Page</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            text-align: center;
            margin: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h2, p {
            margin-bottom: 20px;
        }
        button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e53935;
        }
    </style>
    <script>
        function confirmRefund() {
            var refundableAmount = <?= json_encode(number_format($refundableAmount, 2)) ?>;
            var totalAmount = <?= json_encode(number_format($booking['totalamount'], 2)) ?>;
            var deduction = (totalAmount - refundableAmount).toFixed(2);
            return confirm("You are about to refund. A deduction of ₹" + deduction + " will be applied (10%). Do you want to continue?");
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Refund Warning</h2>
    <p>You are eligible for a refund, but 10% will be deducted from the total cost.</p>
    <p>Total amount paid: ₹<?= number_format($booking['totalamount'], 2) ?></p>
    <p>Refundable amount: ₹<?= number_format($refundableAmount, 2) ?></p>
    
    <form method="POST" action="" onsubmit="return confirmRefund();">
        <button type="submit">Continue for Refund</button>
    </form>
</div>

</body>
</html>
