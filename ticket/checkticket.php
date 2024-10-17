<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email']; // Fetch the current user's email
} else {
    header("Location: login.php");
    exit();
}

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

// Fetch active and expired bookings for the current user
$query = "
    SELECT booking_id, busid, arrtime, date, GROUP_CONCAT(seatnumber) AS seatnumbers, totalamount, `from`, `to`
    FROM booked
    WHERE email = ?
    GROUP BY busid, date, arrtime
    ORDER BY date ASC, arrtime ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $user_email); // Bind the email as a string
$stmt->execute();
$result = $stmt->get_result();

// Get current date and time
$currentDateTime = new DateTime(); // Current date and time
$tickets = ['active' => [], 'expired' => []];

// Separate tickets into active and expired based on the current date and time
while ($row = $result->fetch_assoc()) {
    // Create a DateTime object for the ticket's date and arrival time
    $ticketDateTime = new DateTime($row['date'] . ' ' . $row['arrtime']);

    if ($ticketDateTime >= $currentDateTime) {
        $tickets['active'][] = $row; // Add to active if the ticket's date and time are in the future
    } else {
        $tickets['expired'][] = $row; // Add to expired if the ticket's date and time are in the past
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Tickets</title>
    <style>
        .banner {
    width: 100%;
    height: 100%;
    background-image: linear-gradient(rgba(0,0,0,0.75),rgba(0,0,0,0.75)),url(./img/hm.jpg);
    background-size: cover;
    background-position: center;
    }

    .navbar {
        width: 85%;
        margin: auto;
        padding: 35px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        }
        .navbar ul li {
    list-style: none;
    display:inline-block;
    margin: 0 20px;
    position: relative;
}

.navbar ul li a {
    text-decoration: none;
    color: #fff;
    text-transform: uppercase;
}

.navbar ul li::after {
    content: '';
    height: 3px;
    width: 0;
    background: #009688;
    position: absolute;
    left: 0%;
    bottom: -10px;
    transition: 0.5s;
}

.navbar ul li:hover::after {
    width: 100%;
}

        body {
            font-family: 'Lato', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 10px;
        }
        h2, h3 {
            font-size: 24px;
            margin: 20px 0;
            text-align: center;
        }
        .responsive-table {
            list-style-type: none;
            padding: 0;
        }
        .responsive-table li {
            border-radius: 3px;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .table-header {
            background-color: #95A5A6;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .table-row {
            background-color: #ffffff;
            box-shadow: 0 0 9px rgba(0, 0, 0, 0.1);
        }
        .col-1 { flex-basis: 10%; }
        .col-2 { flex-basis: 30%; }
        .col-3 { flex-basis: 20%; }
        .col-4 { flex-basis: 20%; }
        .col-5 { flex-basis: 20%; }
        
        @media all and (max-width: 767px) {
            .table-header { display: none; }
            .table-row { flex-direction: column; }
            .col {
                flex-basis: 100%;
                display: flex;
                padding: 10px 0;
            }
            .col:before {
                color: #6C7A89;
                padding-right: 10px;
                content: attr(data-label);
                flex-basis: 50%;
                text-align: right;
            }
        }
    </style>
</head>
<body>
    <div class="banner"> 
        <div class="navbar">
            <p style="color: white; margin: 0; padding: 0; font-family: 'Londrina Sketch', cursive;"> 
            Hello  
            <?php 
            $email_parts = explode('@', $user_email);  // Split the email at '@'
            echo '         ' . $email_parts[0] . '  !!!';
            ?>
            </p>
            <ul>
                <li><a href="../home/newhome.php">HOME</a></li>
                <li><a href="../index/index.php">MAKE BOOKING</a></li>
                <!-- <li><a href="../ticket/checkticket.php">CHECK TICKETS</a></li>
                <li><button onclick="logoutUser()">LOGOUT</button></li> -->
            </ul>
        </div>
    </div>
    <div class="container">
        <h2>Your Reservations</h2>

        <!-- Active Tickets -->
        <h3>Active Tickets</h3>
        <ul class="responsive-table">
            <li class="table-header">
                <div class="col col-1">Bus ID</div>
                <div class="col col-2">Date</div>
                <div class="col col-3">Arrival Time</div>
                <div class="col col-4">Seat Numbers</div>
                <div class="col col-5">Total Amount</div>
                <div class="col col-5">Action</div>
            </li>

            <?php if (empty($tickets['active'])): ?>
                <li class="table-row">
                    <div class="col col-1" data-label="No Active Reservations" colspan="5">No active reservations available.</div>
                </li>
            <?php else: ?>
                <?php foreach ($tickets['active'] as $ticket): ?>
                <li class="table-row">
                    <div class="col col-1" data-label="Bus ID"><?= $ticket['busid'] ?></div>
                    <div class="col col-2" data-label="Date"><?= $ticket['date'] ?></div>
                    <div class="col col-3" data-label="Arrival Time"><?= $ticket['arrtime'] ?></div>
                    <div class="col col-4" data-label="Seat Numbers"><?= $ticket['seatnumbers'] ?></div>
                    <div class="col col-5" data-label="Total Amount"><?= $ticket['totalamount'] ?></div>
                    <div class="col col-5" data-label="Action">
                        <a href="refund.php?seatnumbers=<?= urlencode($ticket['seatnumbers']) ?>&date=<?= urlencode($ticket['date']) ?>&busid=<?= urlencode($ticket['busid']) ?>&arrtime=<?= urlencode($ticket['arrtime']) ?>" class="btn btn-warning">Refund</a>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Expired Tickets (only if they exist) -->
        <?php if (!empty($tickets['expired'])): ?>
        <h3>Past Reservations</h3>
        <ul class="responsive-table">
            <li class="table-header">
                <div class="col col-1">Bus ID</div>
                <div class="col col-2">Date</div>
                <div class="col col-3">Arrival Time</div>
                <div class="col col-4">Seat Numbers</div>
                <div class="col col-5">Total Amount</div>
            </li>
            <?php foreach ($tickets['expired'] as $ticket): ?>
            <li class="table-row">
                <div class="col col-1" data-label="Bus ID"><?= $ticket['busid'] ?></div>
                <div class="col col-2" data-label="Date"><?= $ticket['date'] ?></div>
                <div class="col col-3" data-label="Arrival Time"><?= $ticket['arrtime'] ?></div>
                <div class="col col-4" data-label="Seat Numbers"><?= $ticket['seatnumbers'] ?></div>
                <div class="col col-5" data-label="Total Amount"><?= $ticket['totalamount'] ?></div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</body>
</html>
