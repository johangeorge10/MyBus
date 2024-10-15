<?php
// Check if form is submitted
if (isset($_POST['submitBus'])) {
    $busid = $_POST['busid'];
    $conn = new mysqli("localhost", "root", "", "mydb");

    // Check if bus_id exists in BusInfo table
    $query = "SELECT * FROM busInfo WHERE busid='$busid'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Bus exists, check if any schedule data exists for this bus
        $schedule_query = "SELECT * FROM busschedule WHERE busid='$busid'";
        $schedule_result = mysqli_query($conn, $schedule_query);

        if (mysqli_num_rows($schedule_result) > 0) {
            // Display existing schedule data
            echo "<h3>Existing Bus Schedule for Bus ID: $busid</h3>";
            echo "<table border='1'><tr><th>Location</th><th>Time</th><th>Order of Stop</th></tr>";

            while ($row = mysqli_fetch_assoc($schedule_result)) {
                echo "<tr><td>{$row['location_name']}</td><td>{$row['bus_time']}</td><td>{$row['stop_order']}</td></tr>";
            }

            echo "</table>";
        } else {
            // No schedule exists, prompt admin to add bus stops
            echo "<h3>No schedule found for Bus ID: $busid</h3>";
            echo "<form method='post' action='BusSchedule.php'>";
            echo "<label for='num_stops'>Enter Number of Bus Stops:</label>";
            echo "<input type='number' id='num_stops' name='num_stops' required><br>";
            echo "<input type='hidden' name='busid' value='$busid'>";

            // Exit button to return to the main BusSchedule.php
            echo "<button type='button' onclick='exitPage()' style='margin-right: 10px;'>Exit</button>";

            echo "<input type='submit' name='createSchedule' value='Add Bus Stops'>";
            echo "</form>";
        }
    } else {
        echo "<script>alert('Bus ID does not exist in the system. Please check again.')</script>";
    }
}

// Handle adding the bus stops
if (isset($_POST['createSchedule'])) {
    $busid = $_POST['busid'];
    $num_stops = $_POST['num_stops'];

    echo "<h3>Adding Schedule for Bus ID: $busid</h3>";
    echo "<form method='post' action='BusSchedule.php'>";

    // Loop through the number of stops to get location, time, and order of stop
    for ($i = 1; $i <= $num_stops; $i++) {
        echo "<h4>Stop $i</h4>";
        echo "<label for='location_$i'>Location Name:</label>";
        echo "<input type='text' id='location_$i' name='location[]' required><br>";

        echo "<label for='time_$i'>Time (HH:MM:SS):</label>";
        echo "<input type='time' id='time_$i' name='time[]' required><br>";

        echo "<label for='order_$i'>Order of Stop:</label>";
        echo "<input type='number' id='order_$i' name='order[]' required><br><br>";
    }

    // Exit button to return to the main BusSchedule.php
    echo "<button type='button' onclick='exitPage()' style='margin-right: 10px;'>Exit</button>";

    echo "<input type='hidden' name='busid' value='$busid'>";
    echo "<input type='submit' name='saveSchedule' value='Save Schedule'>";
    echo "</form>";
}

// Save the new schedule data into the database
if (isset($_POST['saveSchedule'])) {
    $busid = $_POST['busid'];
    $locations = $_POST['location'];
    $times = $_POST['time'];
    $orders = $_POST['order'];

    $conn = new mysqli("localhost", "root", "", "mydb");

    for ($i = 0; $i < count($locations); $i++) {
        $location = $locations[$i];
        $time = $times[$i];
        $order = $orders[$i];

        // Insert the schedule into the BusSchedule table
        $query = "INSERT INTO BusSchedule (busid, location_name, bus_time, stop_order)
                  VALUES ('$busid', '$location', '$time', '$order')";

        if (!mysqli_query($conn, $query)) {
            echo "Error inserting schedule: " . mysqli_error($conn);
        }
    }

    echo "<script>
            alert('Bus schedule added successfully!');
            window.location.href = 'admindash.php';
          </script>";
    
}
?>

<div class="internal" style="display: none;" id="in-7">
    <h2>Bus Schedule Management</h2>
    <form method="post" action="BusSchedule.php">
        <label for="busid">Enter Bus ID:</label>
        <input type="number" id="busid" name="busid" required><br><br>
        <input type="submit" name="submitBus" value="Check Bus Schedule">
    </form>
</div>

<script>
// JavaScript function to exit and redirect back to the BusSchedule.php page
function exitPage() {
    window.location.href = 'Admindash.php'; // Redirect to the main BusSchedule.php page
}
</script>
