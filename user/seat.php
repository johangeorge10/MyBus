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

if (isset($_POST["bus_number"])) {
  $busNumber = $_POST["bus_number"];
  $_SESSION['busno'] = $busNumber;
} else {
  $busNumber = $_SESSION['busno'];
}

if (isset($_POST["date"])) {
  $departDate = $_POST["date"];
  $_SESSION['date'] = $departDate;
} else {
  $departDate = $_SESSION['date'];
}

$sql = "SELECT * FROM businfo WHERE busid='$busNumber'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $_SESSION['busname'] = $row["busname"];
  $_SESSION['startingpoint'] = $row["startingpoint"];
  $_SESSION['destination'] = $row["destination"];
  $_SESSION['deptime'] = $row["deptime"];
  $_SESSION['arrtime'] = $row["arrtime"];

  $destination = $row["destination"];
  $departure = $row["startingpoint"];
  $capacity = $row['seatcapacity'];
  $price = $row['cost']; // Get the cost directly
  $departTime = $row["arrtime"];
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
    
  </style>
</head>
<body>
  <h1>Bus Seat Selection</h1>
  <div class="ticket-box">
    <h2>Ticket Description</h2>
    <div class="ticket-info">
      <p><strong>Destination:</strong> <?php echo $destination; ?></p>
      <p><strong>Bus Number:</strong> <?php echo $busNumber; ?></p>
      <p><strong>Departure:</strong> <?php echo $departure; ?></p>
      <p><strong>Price:</strong> <span class="ticket-price">$<span id="totalPrice"><?php echo $price; ?></span></span></p>
      <p><strong>Depart Date:</strong> <?php echo $departDate; ?></p>
      <p><strong>Depart Time:</strong> <?php echo $departTime; ?></p>
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

    $rows = [25, 21, 17, 13, 5, 1]; // Row starting points
    foreach ($rows as $rowStart) {
      echo "<div class='row'>";
      for ($i = $rowStart; $i < $rowStart + 5; $i++) {
        if ($i <= $capacity && !in_array($i, $bseats)) {
          echo "<div class='seat' data-seat='$i' onclick='toggleSeat(this)'>$i</div>";
        } else {
          echo "<div class='occupied'>$i</div>";
        }
      }
      echo "</div>";
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
    }

    function updateTotalPrice() {
      const totalPrice = selectedSeats.length * seatPrice;
      document.getElementById('totalPrice').innerText = totalPrice;
    }

    function redirectToCustomerPage() {
      // Redirect to the booking page with selected seats
      if (selectedSeats.length > 0) {
        // Send selected seats to the customer page
        const seats = selectedSeats.join(',');
        window.location.href = `../index/customer.php?seats=${seats}`;
      } else {
        alert('Please select at least one seat.');
      }
    }
  </script>
</body>
</html>

























