<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$departDate = $_POST["date"];
$from = $_POST["from"];
$to = $_POST["to"];

// Save form data as session variables
$_SESSION['departDate'] = $departDate;
$_SESSION['from'] = $from;
$_SESSION['to'] = $to;

// First SQL query to find buses based on the form input (intermediate stops)
$sql = "SELECT DISTINCT
            b.busid,
            b.busname,
            bs_from.location_name AS from_location,
            bs_to.location_name AS to_location,
            bs_from.bus_time AS deptime,
            bs_to.bus_time AS arrtime
        FROM
            BusInfo b
        INNER JOIN
            BusSchedule bs_from ON b.busid = bs_from.busid
        INNER JOIN
            BusSchedule bs_to ON b.busid = bs_to.busid
        WHERE
            bs_from.location_name = ? AND
            bs_to.location_name = ? AND
            bs_from.stop_order < bs_to.stop_order";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Fallback query if no results found in BusSchedule (check BusInfo for direct routes)
    $sql_fallback = "SELECT
                        busid,
                        busname,
                        startingpoint AS from_location,
                        destination AS to_location,
                        deptime,
                        arrtime
                    FROM
                        BusInfo
                    WHERE
                        startingpoint = ? AND destination = ?";

    $stmt_fallback = $conn->prepare($sql_fallback);

    if (!$stmt_fallback) {
        die("SQL error: " . $conn->error);
    }

    $stmt_fallback->bind_param("ss", $from, $to);
    $stmt_fallback->execute();
    $result = $stmt_fallback->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bus Search Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 50px;
      background-color: #f5f5f5;
      background-image: url("../home/img/bus.jpg");
      background-repeat: no-repeat;
      background-size: cover;
    }
    
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #FFFFFF;
      letter-spacing: 2px;
      text-transform: uppercase;
      font-size: 36px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    table {
      border-collapse: collapse;
      width: 100%;
      background-color: transparent;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    th, td {
      padding: 10px;
      text-align: left;
      background-color: rgba(255, 255, 255, 0.8);
    }

    th {
      background-color: #007bff;
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
    }

    td {
      background-color: rgba(255, 255, 255, 0.5);
    }

    tr:nth-child(even) {
      background-color: rgba(230, 230, 230, 0.8);
    }

    tr:hover {
      background-color: rgba(0, 123, 255, 0.6);
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    tr:hover td {
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>Available Buses</h1>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Bus ID</th>
        <th>Bus Name</th>
        <th>From</th>
        <th>To</th>
        <th>Departure Time</th>
        <th>Arrival Time</th>
        <th>Select</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
        <td><?php echo $row["busid"]; ?></td>
        <td><?php echo $row["busname"]; ?></td>
        <td><?php echo $row["from_location"]; ?></td>
        <td><?php echo $row["to_location"]; ?></td>
        <td><?php echo $row["deptime"]; ?></td>
        <td><?php echo $row["arrtime"]; ?></td>
        <td>
          <form action="../user/seat.php" method="POST">
            <input type="hidden" name="bus_number" value="<?php echo $row["busid"]; ?>">
            <input type="hidden" name="date" value="<?php echo $departDate; ?>">
            <input type="hidden" name="from" value="<?php echo $from; ?>">
            <input type="hidden" name="to" value="<?php echo $to; ?>">
            <input type="hidden" name="busname" value="<?php echo $row["busname"]; ?>">
            <input type="hidden" name="deptime" value="<?php echo $row["deptime"]; ?>">
            <input type="hidden" name="arrtime" value="<?php echo $row["arrtime"]; ?>">
            <button class="btn" type="submit">Select Seat</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No buses found for the selected route.</p>
  <?php endif; ?>

  <?php
  // Close connection
  $stmt->close();
  $conn->close();
  ?>
</body>
</html>
