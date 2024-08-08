<!-- PCEtLSANCsKpIDIwMjQgQUouIEFsbCByaWdodHMgcmVzZXJ2ZWQuDQpUaGlzIGNvZGUgaXMgcGFydCBvZiB0aGUgTXlCdXMgcHJvamVjdCBhbmQgbWF5IG5vdCBiZSB1c2VkLCBjb3BpZWQsIG1vZGlmaWVkLCBvciBkaXN0cmlidXRlZCBleGNlcHQgYXMNCmV4cHJlc3NseSBhdXRob3JpemVkIHVuZGVyIHRoZSB0ZXJtcyBvZiB0aGUgbGljZW5zZSBhZ3JlZW1lbnQuDQotLT4NCg==-->
<?php
if (isset($_POST['busid']) && isset($_POST['status'])) {
    // Get the submitted busid, starting point, destination, status, arrival time, destination time, and cost from the form
    $busid = $_POST['busid'];
    $status = $_POST['status'];
    $startingpoint = $_POST['startingpoint'];
    $destination = $_POST['destination'];
    $arrtime = $_POST['arrtime'];
    $deptime = $_POST['deptime'];
    $cost = $_POST['cost'];
    $distance = $_POST['distance'];

    // Perform the database update query for status, starting point, destination, arrival time, destination time, cost, and distance
    $conn = new mysqli("localhost", "root", "", "mydb");
    $updateQuery = "UPDATE businfo SET status = '$status', startingpoint = '$startingpoint', destination = '$destination', arrtime = '$arrtime', deptime = '$deptime', cost = '$cost', distance = '$distance' WHERE busid = '$busid'";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        // Update successful, you can display a success message or redirect the user if needed
        echo "<script>alert('Bus information updated successfully!');</script>";
    } else {
        // Update failed, you can display an error message or handle it as per your requirement
        echo "<script>alert('Bus information update failed!');</script>";
    }
}

// Fetch the bus information from the database for the popup modal
function getBusInfo($conn, $busid) {
    $query = "SELECT * FROM businfo WHERE busid = '$busid'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return mysqli_fetch_assoc($result);
    }

    // Return empty array if no result found
    return array();
}
?>

<div class="internal" style="display: none;" id="in-2">
    <h1><u>Routing</u></h1>

    <form method="post" action="">
        <label for="busid" style="color: white; margin-left:30px;">Bus ID:</label>
        <input type="text" style="margin-left:30px; width:150px; display:block;" placeholder="Enter the BUSID" name="busid" id="busid">
        <input type="submit" style="margin-left:30px;" name="search" value="Search">
    </form>

    <?php
    if (isset($_POST['search'])) {
        // Get the user input (busid) from the form
        $busid = $_POST['busid'];

        // Perform the database query to find the busid
        $conn = new mysqli("localhost", "root", "", "mydb");
        $quer = "SELECT * FROM businfo WHERE busid = '$busid'";
        $result = $conn->query($quer);

        if ($result && $result->num_rows > 0) {
            echo "<h2>Bus Information Found:</h2>";

            echo "<table>";
            echo "<tr><th>BUS ID</th><th>BUS NAME</th><th>FROM_LOC</th><th>DESTINATION</th><th>ARRIVAL TIME</th><th>DESTINATION TIME</th><th>COST</th><th>DISTANCE</th><th>STATUS</th><th>ACTION</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                $busid_found = $row['busid'];
                $busname_found = $row['busname'];
                $start = $row['startingpoint'];
                $end = $row['destination'];
                $arrtime = $row['arrtime'];
                $deptime = $row['deptime'];
                $cost = $row['cost'];
                $distance = $row['distance'];
                $status = $row['status'];

                echo "<tr>";
                echo "<td>" . $busid_found . "</td>";
                echo "<td>" . $busname_found . "</td>";
                echo "<td>" . $start . "</td>";
                echo "<td>" . $end . "</td>";
                echo "<td>" . $arrtime . "</td>";
                echo "<td>" . $deptime . "</td>";
                echo "<td>$" . $cost . "</td>";
                echo "<td>" . $distance . " KM</td>";
                echo "<td>" . $status . "</td>";
                echo '<td><button onclick="openModal(\'' . $busid_found . '\', \'' . $start . '\', \'' . $end . '\', \'' . $arrtime . '\', \'' . $deptime . '\', \'' . $cost . '\', \'' . $distance . '\')">Edit</button></td>'; // Edit button with onclick event to open modal
                echo "</tr>";
            }

            echo "</table>";
        } else {
            // Bus ID not found
            echo "<h2>Bus ID not found</h2>";
        }
    }
    ?>
</div>

<!-- Add the modal (popup window) -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1;">
    <div style="background-color: white; max-width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 4px; position: relative; overflow-y: auto; max-height: 80%;">
        <span style="float: right; cursor: pointer;" onclick="closeModal()">&#10006;</span>
        <h3>Edit Bus Information</h3>
        <form id="editForm" method="post" action="">
            <input type="hidden" id="busidInput" name="busid" value="">
            <label for="startingpoint">Starting Point:</label>
            <input style="width:100%;" type="text" id="startingpoint" name="startingpoint" value="" required><br>
            <label for="destination">Destination:</label>
            <input style="width:100%;" type="text" id="destination" name="destination" value="" required><br>
            <label for="arrtime">Arrival Time:</label>
            <input style="width:100%;" type="text" id="arrtime" name="arrtime" value="" required><br>
            <label for="deptime">Destination Time:</label>
            <input style="width:100%;" type="text" id="deptime" name="deptime" value="" required><br>
            <label for="cost">Cost:</label>
            <input style="width:100%;" type="text" id="cost" name="cost" value="" required><br>
            <label for="distance">Distance (in KM):</label>
            <input style="width:100%;" type="number" id="distance" name="distance" value="" required><br>
            <label><input type="radio" name="status" value="active" id="statusInput"> Active</label><br>
            <label><input type="radio" name="status" value="not active" id="statusInput"> Not Active</label><br>

            <!-- Add some spacing below the radio buttons -->
            <div style="margin-bottom: 20px;"></div>

            <!-- Position the Save button at the very bottom of the modal -->
            <div style="text-align: center;">
                <button style="color: white; background-color: blue; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;" onclick="saveChanges()">Save</button>
            </div>
        </form>
    </div>
</div>



<!-- Add the JavaScript code to handle the modal -->
<script>
    function openModal(busid, startingpoint, destination, arrtime, deptime, cost, distance) {
        document.getElementById('busidInput').value = busid;
        document.getElementById('startingpoint').value = startingpoint;
        document.getElementById('destination').value = destination;
        document.getElementById('arrtime').value = arrtime;
        document.getElementById('deptime').value = deptime;
        document.getElementById('cost').value = cost;
        document.getElementById('distance').value = distance;
        document.getElementById('editModal').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    function saveChanges() {
        document.getElementById('editForm').submit();
    }
</script>

<!-- 
© 2024 AJ. All rights reserved.
This code is part of the MyBus project and may not be used, copied, modified, or distributed except as
expressly authorized under the terms of the license agreement.
-->
