<!DOCTYPE html>
<html>
<head>
  <title>Payment Successful</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .tick {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background-color: #12af39;
      margin: 0 auto;
      margin-bottom: 20px;
    }

    .tick:before {
      content: "\2714";
      font-size: 80px;
      color: #ffffff;
    }

    h1 {
      color: #333;
      margin-bottom: 10px;
    }

    p {
      color: #777;
      margin-bottom: 15px;
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .button:hover {
      background-color: #45a049;
    }

    .booking-info {
      margin-top: 20px;
      text-align: left;
    }

    .booking-info li {
      list-style-type: none;
      padding: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="tick"></div>
    <h1>Payment Successful</h1>
    <p>Your payment has been successfully processed.</p>
    <p>Thank you for your Booking!</p>

    <h2>Booking Information</h2>
    <ul class="booking-info" id="booking-info"></ul>

    <a href="#" class="button" id="return-home">Return to Home</a>
  </div>

  <script>
    // Retrieve data from session storage
    const name = sessionStorage.getItem('name');
const phone = sessionStorage.getItem('phone');
const date = sessionStorage.getItem('D_Date');
const from = sessionStorage.getItem('from');
const to = sessionStorage.getItem('to');
const busid = sessionStorage.getItem('busNumber');
const arrtime = sessionStorage.getItem('arrTime');
const seats = sessionStorage.getItem('seats');
const totalamount = sessionStorage.getItem('totalPrice');
const cost = sessionStorage.getItem('cost');
const totalnumberofseats = sessionStorage.getItem('totalSeats');

// Display booking information
const bookingInfo = document.getElementById('booking-info');
bookingInfo.innerHTML = `
  <li><strong>Name:</strong> ${name}</li>
  <li><strong>Phone:</strong> ${phone}</li>
  <li><strong>Booking Date:</strong> ${date}</li>
  <li><strong>Route:</strong> From ${from} to ${to}</li>
  <li><strong>Bus ID:</strong> ${busid}</li>
  <li><strong>Arrival Time:</strong> ${arrtime}</li>
  <li><strong>Seats:</strong> ${seats}</li>
  <li><strong>Total Amount:</strong> ${totalamount}</li>
`;

// Store booking information in the database via PHP
const bookingData = {
  name: name,
  phone: phone,
  date: date,
  from: from,
  to: to,
  busid: busid,
  arrtime: arrtime,
  seatnumber: seats,
  totalamount: totalamount,
  cost: cost,
  totalnumberofseats: totalnumberofseats,

};

fetch('sucessfull.php', {
  method: 'POST',
  headers: {
    'Content-Type':'application/json',
  },
  body: JSON.stringify(bookingData)
})
.then(response => response.json())
.then(data => {
  if (data.status === 'success') {
    console.log('Booking saved successfully:', data.message);
  } else {
    console.error('Error saving booking:', data.message);
  }
})
.catch(error => console.error('Error:', error));

// Clear session variables and redirect to home
document.getElementById('return-home').addEventListener('click', function(event) {
  event.preventDefault();
  fetch('../index/clearingvariables.php')
    .then(response => {
      if (response.ok) {
        sessionStorage.clear();
        window.location.href = "../home/newhome.php";
      }
    })
    .catch(error => console.error('Error:', error));
});
  </script>
<?php
header('Content-Type:application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Retrieve data from JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if data is not empty
    if (!empty($data)) {
        $name = $data['name'];
        $phonenumber = $data['phone'];
        $date = $data['date'];
        $from = $data['from'];
        $to = $data['to'];
        $busid = $data['busid'];
        $arrtime = $data['arrtime'];
        $seatnumber = $data['seatnumber'];
        $totalamount = $data['totalamount'];
        $cost = $data['cost'];
        $totalnumberofseats = $data['totalnumberofseats'];


        // Prepare and bind the SQL query
        $stmt = $conn->prepare("INSERT INTO booked (busid, arrtime, date, seatnumber, name, phonenumber, totalamount, cost, totalnumberofseats, `from`, `to`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssiiiss", $busid, $arrtime, $date, $seatnumber, $name, $phonenumber, $totalamount, $cost, $totalnumberofseats, $from, $to);

        // Execute the statement and check if the insertion was successful
        if ($stmt->execute()) {
            //echo json_encode(["status" => "success", "message" => "Booking stored successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "No data received."]);
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
</body>
</html>



























<!-- //php only -->










<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Retrieve data from JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if data is not empty
    if (!empty($data)) {
        $name = $data['name'];
        $phonenumber = $data['phone'];
        $date = $data['date'];
        $from = $data['from'];
        $to = $data['to'];
        $busid = $data['busid'];
        $arrtime = $data['arrtime'];
        $seatnumber = $data['seatnumber'];
        $totalamount = $data['totalamount'];
        $cost = $data['cost'];
        $totalnumberofseats = $data['totalnumberofseats'];

        // Prepare and bind the SQL query
        $stmt = $conn->prepare("INSERT INTO booked (busid, arrtime, date, seatnumber, name, phonenumber, totalamount, cost, totalnumberofseats, `from`, `to`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssiiiss", $busid, $arrtime, $date, $seatnumber, $name, $phonenumber, $totalamount, $cost, $totalnumberofseats, $from, $to);

        // Execute the statement and check if the insertion was successful
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Booking stored successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "No data received."]);
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>