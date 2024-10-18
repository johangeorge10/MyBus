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

$fromStopOrder = null;
$toStopOrder = null;
// $fixCost = null;

if (isset($_POST["from_stop_order"])) {
    $fromStopOrder = $_POST["from_stop_order"];
} elseif (isset($_SESSION['from_stop_order'])) {
    $fromStopOrder = $_SESSION['from_stop_order'];
}

if (isset($_POST["to_stop_order"])) {
    $toStopOrder = $_POST["to_stop_order"];
} elseif (isset($_SESSION['to_stop_order'])) {
    $toStopOrder = $_SESSION['to_stop_order'];
}

// if (isset($_POST["fixCost"])) {
//     $fixCost = $_POST["fixCost"];
// } elseif (isset($_SESSION['fixCost'])) {
//     $fixCost = $_SESSION['fixCost'];
// }

$busNumber = isset($_POST["bus_number"]) ? $_POST["bus_number"] : $_SESSION['busno'];
$departDate = isset($_POST["date"]) ? $_POST["date"] : $_SESSION['date'];
$fromLocation = isset($_POST["from"]) ? $_POST["from"] : $_SESSION['from'];
$toLocation = isset($_POST["to"]) ? $_POST["to"] : $_SESSION['to'];
$busName = isset($_POST["busname"]) ? $_POST["busname"] : $_SESSION['busname'];
$departTime = isset($_POST["deptime"]) ? $_POST["deptime"] : $_SESSION['deptime'];
$arrTime = isset($_POST["arrtime"]) ? $_POST["arrtime"] : $_SESSION['arrtime'];


// Conditional calculation for stop difference
$stopdiff = 0; // Default value if no data is available
if (isset($fromStopOrder) && isset($toStopOrder)) {
    $stopdiff = $toStopOrder - $fromStopOrder; // Calculate only if both are set
}

$hasStationData = isset($fromStopOrder) && isset($toStopOrder);
// Time travel calculation
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
    $fixCost =$row['fixCost'];
    $incrementFare =$row['incrementFare'];
} else {
    // Bus not found
    die("Invalid bus number");
}

// Fetch booked seats
$bseats = [];
$sqlBookedSeats = "SELECT seatnumber FROM booked WHERE busid='$busNumber' AND arrtime='$arrTime' AND date='$departDate'";
$resultBooked = $conn->query($sqlBookedSeats);
if ($resultBooked->num_rows > 0) {
    while ($row = $resultBooked->fetch_assoc()) {
        $bseats[] = $row['seatnumber'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Seat Selection</title>
    <link rel="stylesheet" type="text/css" href="seat.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
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
            <p><strong>Price:</strong> <span class="ticket-price">$<span id="totalPrice">0</span></span></p>
            <p><strong>Depart Date:</strong> <?php echo $departDate; ?></p>
            <p><strong>Depart Time:</strong> <?php echo $departTime; ?></p>
            <p><strong>Arrival Time:</strong> <?php echo $arrTime; ?></p>
            <p><strong>Travel Time:</strong> <?php echo $travelTime; ?></p>
            <p><strong>Total Number of Tickets:</strong> <span id="totalTickets">0</span></p>
            <p><strong>Seats:</strong> <span id="selectedSeatsDisplay">NO SEATS BOOKED</span></p>
            <button class="btn" onclick="redirectToCustomerPage()">Book Selected Seats</button>
        </div>
        <!-- hidden form -->
        <form id="bookingForm" action="../index/customer.php" method="POST" style="display: none;">
            <input type="hidden" name="seats" id="seatsInput">
            <input type="hidden" name="totalSeats" id="totalSeatsInput">
            <input type="hidden" name="totalPrice" id="totalPriceInput">
            <input type="hidden" name="bus_number" value="<?php echo $busNumber; ?>">
            <input type="hidden" name="date" value="<?php echo $departDate; ?>">
            <input type="hidden" name="from" value="<?php echo $fromLocation; ?>">
            <input type="hidden" name="to" value="<?php echo $toLocation; ?>">
            <input type="hidden" name="busname" value="<?php echo $busName; ?>">
            <input type="hidden" name="deptime" value="<?php echo $departTime; ?>">
            <input type="hidden" name="arrtime" value="<?php echo $arrTime; ?>">
        </form>
    </div>

    <div class="seat-layout">
    <?php
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
                echo "<div class='occupied' style='margin-top: 15px;'>$i</div>";
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
    // Set this variable based on PHP logic
    const useFixCost = <?php echo $hasStationData ? 'true' : 'false'; ?>; // true if $hasStationData is true
    const seatPrice = <?php echo $price; ?>; // Base price per seat
    const fixCost = <?php echo $fixCost; ?>; // Fixed cost from PHP
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
        

        let pricePerSeat;

        // Decide which price to use based on the useFixCost variable
        if (useFixCost) {
            const incrementFare = <?php echo $incrementFare; ?>; // Fetch incrementFare from PHP
            const stopdiff = <?php echo $stopdiff; ?>; // Fetch stopdiff from PHP
            pricePerSeat = seatPrice + (stopdiff * incrementFare); // Use base price + increment fare
        } else {
            pricePerSeat = fixCost; // Use the fixed cost
        }

        // Total price for all selected seats
        const totalPrice = selectedSeats.length * pricePerSeat;

        // Update the displayed total price
        document.getElementById('totalPrice').innerText = totalPrice;

        // Update the hidden input field with the total price
        document.getElementById('totalPriceInput').value = totalPrice;
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
        // Check if any seats are selected
        if (selectedSeats.length === 0) {
            alert('Please select at least one seat before proceeding.');
            return; // Prevent form submission if no seats are selected
        }

        // Set values in hidden form fields
        document.getElementById('seatsInput').value = selectedSeats.join(',');
        document.getElementById('totalSeatsInput').value = selectedSeats.length;

        // Update the hidden totalPrice field with the calculated value
        updateTotalPrice();

        // Submit the form
        document.getElementById('bookingForm').submit();
    }
</script>

</body>
</html>
