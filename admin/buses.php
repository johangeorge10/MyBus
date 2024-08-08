<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<?php 

if(isset($_POST['delete']))
{
  $conn = new mysqli("localhost", "root", "", "mydb");
$deleteid=$_POST['deleteid'];
mysqli_query($conn,"DELETE FROM businfo WHERE busid='$deleteid'");

echo "<script> location.href='admindash.php' </script>";
}

?>

      

      <div class="internal" style="display: none;" id="in-4">
        <button class="add-bus-button" onclick="showAddBusPopup()">Add Bus</button>
        <div id="add-bus" class="section">
          <table>
            <thead>
              <tr>
                <th>Sl. No</th>
                <th>Bus ID</th>
                <th>Bus Name</th>
                <th>Bus License Number</th>
                <th>Seat Capacity</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="bus-table-body">
            <?php 
                $conn = new mysqli("localhost", "root", "", "mydb");
             $quer = "select * from businfo;";
             $result = mysqli_query($conn, $quer);
             if($result!=null)
             {
             $count=mysqli_num_rows($result);
              $n=0;
             while ($row=mysqli_fetch_assoc($result))
             {
              $n++;
            $busid= $row['busid'];
            $busname= $row['busname'];
            $licenseno= $row['licenseno'];
             $capacity=$row['seatcapacity'];
            $status= $row['status'];
              ?>
              <form action="admindash.php" method="POST">
                <input type="text" name="deleteid" style="display:none !important" value='<?php echo "$busid" ?> '>
              <tr style="color: rgb(0, 0, 0);" >
              <td> <?php echo "$n" ?></td>
                <td><?php echo " $busid" ?></td>
                <td><?php echo " $busname" ?></td>
                <td><?php echo" $licenseno"?> </td>
                <td><?php echo "$capacity"?></td>
                <td><?php echo "$status"?></td>
                <td>
                  <button  type="button" name="edit" onclick="showEditBusPopup(this)" >Edit</button>
                  <button   name="delete" type="submit" >Delete</button>
                </td>
              </tr>
             </form>
              <?php
             }
            }
             ?>
              <!-- Add more rows for other buses -->
            </tbody>
          </table>
        </div>
      <?php
      if(isset($_POST['addbus']))
      {
        $conn = new mysqli("localhost", "root", "", "mydb");
      $busname=$_POST['busname'];
      $licenseno=$_POST['licenseno'];
      $capacity=$_POST['capacity'];
      $quer = "insert into businfo(busname,licenseno,seatcapacity,status) values('$busname','$licenseno','$capacity','not active')";
      $sql_result = mysqli_query($conn, $quer);
  

      }

      ?>

        <div class="popup" id="add-bus-popup">
          <div class="popup-header">
            <h2 style="display: inline-block;">Add Bus Details</h2>
            <button class="close-button"  onclick="hideAddBusPopup()">X</button>
          </div>
          <form class="popup-form" action="admindash.php" method="POST">
            <label for="bus-name">Bus Name:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="busname" type="text" id="add-bus-name-input" placeholder="Enter bus name" required>
      
            <label for="bus-number">Bus License Number:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="licenseno" type="text" id="add-bus-license-input" placeholder="Enter license number" required>
      
            <label for="seat-count">Seat Capacity:</label>
            <input style="width: 150px; height: 40px; " name="capacity"  type="number" id="add-seat-count-input" placeholder="Enter seat capacity" required>
      
            <input style="width: 90px;" type="submit" value="Add" name="addbus">
          </form>
        </div>
        <?php
      if(isset($_POST['editsave']))
      {
        $conn = new mysqli("localhost", "root", "", "mydb");
      $busname=$_POST['busname'];
      $licenseno=$_POST['licenseno'];
      $capacity=$_POST['capacity'];
      $busid=$_POST['busid'];
      $quer = "update businfo set busname='$busname', licenseno='$licenseno',seatcapacity='$capacity' where busid='$busid'";
      $sql_result = mysqli_query($conn, $quer);

     echo "<script> location.href='admindash.php'</script>";
      }

      ?>
      <?php 
  

      ?>
      
        <div class="popup" id="edit-bus-popup">
          <div class="popup-header">
            <h2 style="display: inline-block;">Edit Bus Details</h2>
            <button class="close-button" onclick="hideEditBusPopup()">X</button>
          </div>
          <form class="popup-form" action="admindash.php" method="POST" >
            <input type="text" name="busid" id="edit-bus-id" style="display:none;" value="" >
            <input type="hidden" id="edit-bus-row-index" value="">
            <label for="bus-name">Bus Name:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="busname" type="text" id="edit-bus-name-input" placeholder="Enter bus name" required>
      
            <label for="bus-number">Bus License Number:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="licenseno" type="text" id="edit-bus-license-input" placeholder="Enter license number" required>
      
            <label for="seat-count">Seat Capacity:</label>
            <input style="width: 150px; height: 40px; " name="capacity"  type="number" id="edit-seat-count-input" placeholder="Enter seat capacity" required>
      
            <input style="width: 90px;" type="submit" value="Save" name="editsave" onclick="saveBusDetails(event)" >
          </form>
        </div>
      
        <div class="overlay" id="overlay"></div>
        <script>
          var busCount = 1; // Counter to track the number of buses
      
          function showAddBusPopup() {
            var addBusPopup = document.getElementById("add-bus-popup");
            var overlay = document.getElementById("overlay");
            addBusPopup.style.display = "block";
            overlay.style.display = "block";
          }
      
          function hideAddBusPopup() {
            var addBusPopup = document.getElementById("add-bus-popup");
            var overlay = document.getElementById("overlay");
            addBusPopup.style.display = "none";
            overlay.style.display = "none";
          }
      
          function showEditBusPopup(link) {
            var row = link.parentNode.parentNode;
            var rowIndex = Array.from(row.parentNode.children).indexOf(row);
            var busid = row.children[1].textContent;
            var busName = row.children[2].textContent;
            var busLicense = row.children[3].textContent;
            var seatCapacity = row.children[4].textContent;
            var editBusPopup = document.getElementById("edit-bus-popup");
            var overlay = document.getElementById("overlay");
            editBusPopup.style.display = "block";
            overlay.style.display = "block";
            document.getElementById('edit-bus-id').value=busid;
            document.getElementById("edit-bus-row-index").value = rowIndex;
            document.getElementById("edit-bus-name-input").value = busName;
            document.getElementById("edit-bus-license-input").value = busLicense;
            document.getElementById("edit-seat-count-input").value = seatCapacity;
          }
      
          function hideEditBusPopup() {
            var editBusPopup = document.getElementById("edit-bus-popup");
            var overlay = document.getElementById("overlay");
            editBusPopup.style.display = "none";
            overlay.style.display = "none";
          }
      
          function addBusDetails(event) {
            event.preventDefault(); // Prevent form submission
      
            // Get input values
            var busName = document.getElementById("add-bus-name-input").value;
            var busLicense = document.getElementById("add-bus-license-input").value;
            var seatCapacity = document.getElementById("add-seat-count-input").value;
      
            // Generate Bus ID with leading zeros
            var busId = "#" + ("000" + busCount).slice(-4);
            busCount++; // Increment bus count for the next bus
      
            // Create a new table row
            var tableBody = document.getElementById("bus-table-body");
            var newRow = document.createElement("tr");
            newRow.style.color = "rgb(0, 0, 0)";
      
            // Populate the row with data
            newRow.innerHTML = `
              <td>${busCount}</td>
              <td>${busId}</td>
              <td>${busName}</td>
              <td>${busLicense}</td>
              <td>${seatCapacity}</td>
              <td>Active</td>
              <td>
                <a href="#" onclick="showEditBusPopup(this)">Edit</a>
                <a href="#" onclick="deleteBus(this)">Delete</a>
              </td>
            `;
      
            // Append the new row to the table
            tableBody.appendChild(newRow);
      
            // Reset form values
            document.getElementById("add-bus-name-input").value = "";
            document.getElementById("add-bus-license-input").value = "";
            document.getElementById("add-seat-count-input").value = "";
      
            // Close the popup
            hideAddBusPopup();
          }
      
          function saveBusDetails(event) {
 
      
            var rowIndex = document.getElementById("edit-bus-row-index").value;
            var row = document.getElementById("bus-table-body").children[rowIndex];
                var busid=document.getElementById('edit-bus-id').value;
            var busName = document.getElementById("edit-bus-name-input").value;
            var busLicense = document.getElementById("edit-bus-license-input").value;
            var seatCapacity = document.getElementById("edit-seat-count-input").value;


      
            row.children[2].textContent = busName;
            row.children[3].textContent = busLicense;
            row.children[4].textContent = seatCapacity;
 

      
            // Close the popup
            hideEditBusPopup();
          }
      
          function deleteBus(link) {
            var row = link.parentNode.parentNode;
            row.parentNode.removeChild(row);
      
            // Reset serial numbers after deleting a row
            var rows = document.querySelectorAll("#bus-table-body tr");
            for (var i = 0; i < rows.length; i++) {
              rows[i].children[0].textContent = i + 1;
            }
          }
        </script>
      </div>
      