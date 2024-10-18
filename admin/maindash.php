<?php
      $conn = new mysqli("localhost", "root", "", "mydb");
     $result= mysqli_query($conn,"select count(*) FROM businfo");
      $result=$result->fetch_row()[0];
      $available=mysqli_query($conn,"select count(*) FROM businfo where status='active'");
      $available=$available->fetch_row()[0];
      $ticketsSoldResult = mysqli_query($conn, "SELECT COUNT(*) FROM booked");
      $ticketsSold = $ticketsSoldResult->fetch_row()[0];

    
    ?>
<div class="main-container">
      <div class="internal"  id="in-1">
      <div class="dashboard">
        <h1 style="padding-bottom: 20px;color: rgb(0, 0, 0);">Bus Administration Dashboard</h1>
        
        <div class="summary">
          <div class="summary-item">
            <span>Total Buses</span><br>
               <label id="total-buses"><?php echo "$result" ?></label>
          </div>
          
          <div class="summary-item">
            <span>Available Buses</span><br>
            <label id="available-buses"><?php echo "$available" ?></label>
          </div>
          
          <div class="summary-item">
            <span>Tickets Sold</span><br>
            <label id="tickets-sold"><?php echo "$ticketsSold" ?></label>
          </div>
        </div>
      </div>
      </div>




  
<script>

function findBus() {

  <?php
      
        $conn = new mysqli("localhost", "root", "", "mydb");
        $busid=2;
       $result= mysqli_query($conn,"select * FROM businfo WHERE busid='$busid'");
      $row=mysqli_fetch_assoc($result);
      $start=$row['startingpoint'];
      $dest=$row['destination'];
      $cost=$row['cost'];
      
    

      ?>
      var busId = document.getElementById("bus-id").value;
      // Make an API request to the server to find the bus by ID

      // Assume the server response includes the bus details
      var startingPoint =' <?php if($start!=null) echo "$start" ; else echo "none" ;?>';
      var destination = ' <?php if($start!=null) echo "$dest" ; else echo "none" ;?>';
      var travelingCost = ' <?php if($start!=null) echo "$$cost" ; else echo "$0" ;?>';

      // Update the bus details on the webpage
      document.getElementById("starting-point").textContent = startingPoint;
      document.getElementById("destination").textContent = destination;
      document.getElementById("traveling-cost").textContent = travelingCost;

      // Show the bus details
      document.getElementById("starting-point-info").classList.add("show");
      document.getElementById("destination-info").classList.add("show");
      document.getElementById("traveling-cost-info").classList.add("show");
   
    }
</script>
