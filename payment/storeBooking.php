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
        $seatnumbers = $data['seatnumber']; // This is the comma-separated seat numbers string
        $totalamount = $data['totalamount'];
        $cost = $data['cost'];
        $totalnumberofseats = $data['totalnumberofseats'];

        // Split the seat numbers by comma to get an array of seat numbers
        $seats = explode(',', $seatnumbers);

        // Prepare the SQL query for each seat booking
        $stmt = $conn->prepare("INSERT INTO booked (busid, arrtime, date, seatnumber, name, phonenumber, totalamount, cost, totalnumberofseats, `from`, `to`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($seats as $seatnumber) {
            // Trim to remove any extra spaces
            $seatnumber = trim($seatnumber);

            // Bind parameters for each seat number and execute the statement
            $stmt->bind_param("isssssiiiss", $busid, $arrtime, $date, $seatnumber, $name, $phonenumber, $totalamount, $cost, $totalnumberofseats, $from, $to);

            if (!$stmt->execute()) {
                echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
                exit();
            }
        }

        echo json_encode(["status" => "success", "message" => "Booking stored successfully."]);

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