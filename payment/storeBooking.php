<?php

// Load the mail configuration from config.php
require __DIR__ . '/../config.php'; // Ensure you have the right path

// Load PHPMailer files from the extracted directory
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $email = $data['email'];  // Retrieve email from data
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

        // Prepare the SQL query for each seat booking, including email
        $stmt = $conn->prepare("INSERT INTO booked (busid, arrtime, date, seatnumber, name, phonenumber, totalamount, cost, totalnumberofseats, email, `from`, `to`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($seats as $seatnumber) {
            // Trim to remove any extra spaces
            $seatnumber = trim($seatnumber);

            // Bind parameters for each seat number and execute the statement
            $stmt->bind_param("isssssiiisss", $busid, $arrtime, $date, $seatnumber, $name, $phonenumber, $totalamount, $cost, $totalnumberofseats, $email, $from, $to);

            if (!$stmt->execute()) {
                echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
                exit();
            }
        }

        echo json_encode(["status" => "success", "message" => "Booking stored successfully."]);

        // Close the statement
        $stmt->close();

        
        $mail = new PHPMailer(true); // Create a new PHPMailer instance

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = $config["mail"]["username"]; // SMTP username
            $mail->Password = $config["mail"]["password"]; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom($config["mail"]["username"], 'BUS MANAGEMENT SYSTEM'); // Sender's email
            $mail->addAddress($email, $name); // Add the recipient's email

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Booking Confirmation';
            $mail->Body = "
            <h1>Booking Confirmation</h1>
            <p>Dear $name,</p>
            <p>Thank you for your booking!</p>
            <p>Here are your booking details:</p>
            <ul>
                <li>Bus ID: $busid</li>
                <li>Arrival Time: $arrtime</li>
                <li>Date: $date</li>
                <li>Seats: " . implode(', ', $seats) . "</li>
                <li>Total Amount: $totalamount</li>
                <li>Cost per Seat: $cost</li>
                <li>Total Number of Seats: $totalnumberofseats</li>
                <li>From: $from</li>
                <li>To: $to</li>
            </ul>
            <p>Thank you for choosing us!</p>
            <p>Best Regards,<br>Your Company Name</p>";

            // Send the email
            if ($mail->send()) {
                echo json_encode(["status" => "success", "message" => "Booking stored successfully and confirmation email sent."]);
            } else {
                echo json_encode(["status" => "success", "message" => "Booking stored successfully, but failed to send confirmation email."]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Booking stored successfully, but failed to send confirmation email: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No data received."]);
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
