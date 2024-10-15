<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
</head>

<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch POST variables
$busNumber = isset($_POST["bus_number"]) ? $_POST["bus_number"] : $_SESSION['busno'];
$departDate = isset($_POST["date"]) ? $_POST["date"] : $_SESSION['date'];
$fromLocation = isset($_POST["from"]) ? $_POST["from"] : $_SESSION['from'];
$toLocation = isset($_POST["to"]) ? $_POST["to"] : $_SESSION['to'];
$busName = isset($_POST["busname"]) ? $_POST["busname"] : $_SESSION['busname'];
$departTime = isset($_POST["deptime"]) ? $_POST["deptime"] : $_SESSION['deptime'];
$arrTime = isset($_POST["arrtime"]) ? $_POST["arrtime"] : $_SESSION['arrtime'];


//time travel calc
$departDateTime = DateTime::createFromFormat('H:i:s', $departTime);
$arrivalDateTime = DateTime::createFromFormat('H:i:s', $arrTime);

// Calculate the difference
$timeDifference = $departDateTime->diff($arrivalDateTime);

// Format the travel time
$travelTime = sprintf("%d hours, %d minutes, %d seconds", $timeDifference->h, $timeDifference->i, $timeDifference->s);

// Store these values in session variables for later use
$_SESSION['busno'] = $busNumber;
$_SESSION['date'] = $departDate;
$_SESSION['from'] = $fromLocation;
$_SESSION['to'] = $toLocation;
$_SESSION['busname'] = $busName;
$_SESSION['deptime'] = $departTime;
$_SESSION['arrtime'] = $arrTime;

$sql = "SELECT * FROM businfo WHERE busid='$busNumber'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $destination = $row["destination"];
    $departure = $row["startingpoint"];
    $capacity = $row['seatcapacity'];
    $price = $row['cost']; // Get the cost directly
} else {
    // Bus not found
    die("Invalid bus number");
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Seat Selection</title>
    <link rel="stylesheet" type="text/css" href="seat.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    <style>
        /* Add any additional styles here */
    </style>
</head>
<body>
    <h1>Bus Seat Selection</h1>
    <div class="ticket-box">
        <h2>Ticket Description</h2>
        <div class="ticket-info">
            <p><strong>Bus Number & Bus Name:</strong> <?php echo $busNumber . " - " . $busName; ?></p>
            <p><strong>Bus:</strong> <?php echo $departure . " - " . $destination; ?></p>
            <p><strong>Destination:</strong> <?php echo $toLocation; ?></p>
            <p><strong>Departure:</strong> <?php echo $fromLocation; ?></p>
            <p><strong>Price:</strong> <span class="ticket-price">$<span id="totalPrice"><?php echo $price; ?></span></span></p>
            <p><strong>Depart Date:</strong> <?php echo $departDate; ?></p>
            <p><strong>Depart Time:</strong> <?php echo $departTime; ?></p>
            <p><strong>Arrival Time:</strong> <?php echo $arrTime; ?></p>
            <p><strong>Travel Time:</strong> <?php echo $travelTime; ?></p>
            <p><strong>Total Number of Tickets:</strong> <span id="totalTickets">0</span></p>
            <p><strong>Seats:</strong> <span id="selectedSeatsDisplay">NO SEATS BOOKED</span></p>
            <button class="btn" onclick="redirectToCustomerPage()">Book Selected Seats</button>
        </div>
    </div>

    <div class="seat-layout">
    <?php
    $bseats = [];
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT seat FROM booked WHERE busid='$busNumber'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $bseats[] = $row['seat'];
    }

    $seatsPerRow = 5; // Number of seats per row
    $totalSeats = $capacity; // This is fetched from the businfo table

    // Create an array of seat numbers from 1 to totalSeats
    $seatNumbers = range(1, $totalSeats); // Create an array from 1 to totalSeats

    // Create a 2D array to hold the seat rows
    $rows = array_chunk($seatNumbers, $seatsPerRow);

    // Reverse the rows so that the last row is displayed first
    $rows = array_reverse($rows);

    foreach ($rows as $row) {
        echo "<div class='row'>"; // Start a new row
        foreach ($row as $i) {
            // Display available or occupied seats
            if (!in_array($i, $bseats)) {
                echo "<div class='seat' data-seat='$i' onclick='toggleSeat(this)'>$i</div>";
            } else {
                echo "<div class='occupied'>$i</div>";
            }
        }
        echo "</div>"; // Close the row
    }
    ?>
    <div class="drv">
        <img src="../images/drv.png" alt="driver seat">
    </div>
</div>

    <script>
        const seatPrice = <?php echo $price; ?>; // Base price per seat
        let selectedSeats = [];

        function toggleSeat(seatElement) {
    const seatNumber = seatElement.dataset.seat;
    seatElement.classList.toggle('selected');
    if (selectedSeats.includes(seatNumber)) {
        selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
    } else {
        selectedSeats.push(seatNumber);
    }
    updateTotalPrice();
    updateSelectedSeatsDisplay(); // Update the display of selected seats
}

function updateTotalPrice() {
    const totalPrice = selectedSeats.length * seatPrice;
    document.getElementById('totalPrice').innerText = totalPrice;
}

// New function to update the display of selected seats and count
function updateSelectedSeatsDisplay() {
    const totalTickets = selectedSeats.length;
    document.getElementById('totalTickets').innerText = totalTickets;

    if (totalTickets > 0) {
        document.getElementById('selectedSeatsDisplay').innerText = selectedSeats.join(', ');
    } else {
        document.getElementById('selectedSeatsDisplay').innerText = ''; // Clear display if no seats are selected
    }
}

        function redirectToCustomerPage() {
            // Redirect to the booking page with selected seats
            if (selectedSeats.length > 0) {
                // Send selected seats to the customer page
                const seats = selectedSeats.join(',');
                window.location.href = `../index/customer.php?seats=${seats}&bus_number=${<?php echo json_encode($busNumber); ?>}&date=${<?php echo json_encode($departDate); ?>}&from=${<?php echo json_encode($fromLocation); ?>}&to=${<?php echo json_encode($toLocation); ?>}&busname=${<?php echo json_encode($busName); ?>}&deptime=${<?php echo json_encode($departTime); ?>}&arrtime=${<?php echo json_encode($arrTime); ?>}`;
            } else {
                alert('Please select at least one seat.');
            }
        }
    </script>
</body>
</html>
