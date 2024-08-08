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

if (isset($_POST["bus_number"]))
{
$busNumber= $_POST["bus_number"];
$_SESSION['busno']=$busNumber;
}
else
{
  $busNumber=$_SESSION['busno'];
}
if (isset($_POST["date"]))
{
$departDate= $_POST["date"];
$_SESSION['date']=$departDate;
}
else
{
  $departDate=$_SESSION['date'];
}
$sql = "SELECT * FROM businfo WHERE busid='$busNumber'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $destination = $row["destination"];
  $departure = $row["startingpoint"];
  $capacity=$row['seatcapacity'];
  $price = calculateTicketPrice($busNumber); // Replace with your own price calculation logic
  $departTime = $row["arrtime"];
} else {
  // Bus not found
  die("Invalid bus number");
}

$conn->close();

function calculateTicketPrice($busNumber) {
  // Replace this with your own price calculation logic
  return 50;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bus Seat Selection</title>
  <link rel="stylesheet" type="text/css" href="seat.css">
</head>
<body>
  <h1>Bus Seat Selection</h1>
  <div class="ticket-box">
    <h2>Ticket Description</h2>
    <div class="ticket-info">
      <p><strong>Destination:</strong> <?php echo $destination; ?></p>
      <p><strong>Bus Number:</strong> <?php echo $busNumber; ?></p>
      <p><strong>Departure:</strong> <?php echo $departure; ?></p>
      <p><strong>Price:</strong> <span class="ticket-price">$<?php echo $price; ?></span></p>
      <p><strong>Depart Date:</strong> <?php echo $departDate; ?></p>
      <p><strong>Depart Time:</strong> <?php echo $departTime; ?></p>
      <button class="btn" onclick="redirectToCustomerPage()">Book Selected Seats</button>
    </div>
  </div>
<?php

$conn = new mysqli($servername, $username, $password, $dbname);
if(isset($_POST['seatbook']))
{
  $busid=$_POST['busid'];
  $seatid=$_POST['seatno'];
  $sql = "insert into booked values('$busid','$seatid')";
  $result =  $result = mysqli_query($conn, $sql);

}
$sql = "select seat from booked where busid='$busNumber'";
$result = mysqli_query($conn, $sql);
$i=0;
$bseats = array(); 
while($row=mysqli_fetch_assoc($result))
{
  $bseats[$i]=$row['seat'];
  $i++;
}

?>

  <div class="seat-layout">
    <div class="row">
      <?php 
for($i=25;($i<=29);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
      ?>

      <div class="seat"><?php echo "$i" ?></div>
      <?php
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
     
    </div>
     <div class="row">
      <?php 
for($i=21;($i<=24);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
    if($i==23)
    {
      ?>
      <div class="seat"><?php echo "$i" ?></div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <?php
    } else {
      ?>

      <div class="seat"><?php echo "$i" ?></div>
      <?php
    }
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
     
    </div>
      <div class="row">
      <?php 
for($i=17;($i<=20);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
    if($i==19)
    {
      ?>
      <div class="seat"><?php echo "$i" ?></div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <?php
    }
    else{
      ?>

      <div class="seat"><?php echo "$i" ?></div>
      <?php
    }
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
     
    </div>
    <div class="row">
      <?php 
for($i=13;($i<=16);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
    if($i==15)
    {
      ?>
      <div class="seat"><?php echo "$i" ?></div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <?php
    }
    else
    {
      ?>

      <div class="seat"><?php echo "$i" ?></div>
  
      <?php
    }
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
     
    </div>
    <div class="row">
      <?php 
for($i=5;($i<=8);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
    if($i==7)
    {
      ?>
      <div class="seat"><?php echo "$i" ?></div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <?php
    }
    else
    {
      ?>

      <div class="seat"><?php echo "$i" ?></div>
  
      <?php
    }
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
     
    </div>
    <div class="row">
      <?php 
for($i=1;($i<=4);$i++)
{
  if(($i<=$capacity)&&(in_array($i, $bseats)==false))
  {
    if($i==3)
    {
      ?>
      <div class="seat"><?php echo "$i" ?></div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <?php
    }
    else
    {
      ?>

      <div class="seat"><?php echo "$i" ?></div>
  
      <?php
    }
  }
  else
  {
    ?>
<div class="occupied"><?php echo "$i" ?></div>
    <?php
  }
}
   ?>
    </div>
    <div class="drv">
        <img src="../images/drv.png" alt="driver seat">
    </div>
  </div>
  
  <script>
// Get all seat elements
const seatElements = document.querySelectorAll('.seat');

// Add price data attribute to each seat element
seatElements.forEach((seat, index) => {
  const price = calculateSeatPrice(index + 1); // Replace with your own price calculation logic
  seat.dataset.price = price; // Store the price as a number, without the currency symbol
});

// Add click event listener to each seat
seatElements.forEach((seat) => {
  seat.addEventListener('click', () => {
    seat.classList.toggle('selected');
      var seatid= seat.innerHTML;
      var busid= <?php echo "$busNumber" ?>

      $.ajax(
{
type: "POST",
url: 'seat.php',
data: {'seatbook': busid, 'busid':busid,'seatno':seatid}

});

    // Update the ticket price in the ticket-info section
    updateTicketPrice();
  });
});

// Function to calculate seat price based on seat number
function calculateSeatPrice(seatNumber) {
  // Replace this with your own price calculation logic
  if (seatNumber <= 10) {
    return 50;
  } else if (seatNumber <= 20) {
    return 40;
  } else {
    return 30;
  }
}

// Function to update the ticket price based on selected seats
function updateTicketPrice() {
  const selectedSeats = document.querySelectorAll('.seat.selected');
  let totalPrice = 0;

  // Calculate the total price of selected seats
  selectedSeats.forEach((seat) => {
    const seatPrice = parseInt(seat.dataset.price);
    if (!isNaN(seatPrice)) {
      totalPrice += seatPrice;
    }
  });

  // Update the ticket price in the ticket-info section
  const ticketPriceElement = document.querySelector('.ticket-info .ticket-price');
  ticketPriceElement.textContent = `$${totalPrice}`;
}

// Update the ticket price initially
updateTicketPrice();

// Redirect to the customer.html page when the "Book Selected Seats" button is clicked
const bookButton = document.querySelector('.btn');
bookButton.addEventListener('click', () => {
  window.location.href = '../index/customer.php';
});

  </script>
</body>
</html>
























