<!DOCTYPE html>
<html>
<head>
  <title>Bus Admin Dashboard</title>
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Ysabeau+SC:wght@200&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admindash.css">

  
<script>
  function newclick(id){
    var i;

    for(i=1;i<=7;i++)
    {
    document.getElementById('in-'+i).style.display='none';
    }

document.getElementById('in-'+id).style.display="block";
  }
</script>

<style>
.internal{
  height:100%;
  width:100%;
}
.main{
  display: flex;
  width: 100%;
}
.sidebar{
  flex-basis:20%;
}
  </style>
  <script>
    function redirectToHomePage() {
      window.location.href = '../home/home.html';
    }
  </script>
  
</head>


<body>
    <div class="header">
        <div class="profile">
          <img src="1.jpg" alt="Profile Picture">
          <span class="name">
        <?php
        session_start();
        if (isset($_SESSION['email'])) {
            echo $_SESSION['email']; // Display the user's email
        }
        ?>
    </span>
        </div>
        <button class="logout" onclick="redirectToHomePage()">Logout</button>
    </div>
 
    <?php
    if (isset($_POST['editsaveemp'])) {
        $conn = new mysqli("localhost", "root", "", "mydb");
        $empname = $_POST['empname'];
        $email = $_POST['email'];
        $phoneno = $_POST['phoneno'];
        $empid = $_POST['empid'];
        echo "<script> alert('$empname,$empid,$email,$phoneno')</script>";
        $quer = "UPDATE empinfo SET empname='$empname', email='$email', phno='$phoneno' WHERE empid='$empid' ";
        $sql_result = mysqli_query($conn, $quer);

    }

    ?>
  
  <div class="main">
    <div class="sidebar">
      <ul>
        <div class="set">
            <li><a class="atag" style="text-decoration:none" onclick="newclick(id)" id="1" >Dashboard</a></li>
        </div>
        <div class="set">
        <li><a class="atag" style="text-decoration:none"  onclick="newclick(id)" id="2">Routes</a></li>
        </div>
        
        <div class="set">
        <li><a class="atag" style="text-decoration:none"  onclick="newclick(id)" id="3">Bookings</a></li>
        </div>
        <div class="set">
        <li><a class="atag" style="text-decoration:none" onclick="newclick(id)" id="4" >Buses</a></li>
        </div>
        <div class="set">
        <li><a class="atag" style="text-decoration:none" onclick="newclick(id)" id="5" >Add New Admin</a></li>
        </div>
        <div class="set">
        <li><a class="atag" style="text-decoration:none" onclick="newclick(id)" id="6" >Employee details</a></li>
        </div>
        <div class="set">
        <li><a class="atag" style="text-decoration:none" onclick="newclick(id)" id="7" >Bus Schedule</a></li>
        </div>
      </ul>
    </div>
  


    <?php 
  include("maindash.php");
    ?>


   <?php 
  include("routing.php");
    ?>


<?php 
  include("booking.php");
    ?>

<?php 
  include("buses.php");
    ?>

    <?php 
  include("addadmin.php");
    ?>

    <?php
  include("empdetails.php");
    ?>

<?php
  include("BusSchedule.php");
    ?>

  </div>
</body>
</html>
