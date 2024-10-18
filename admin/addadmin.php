<?php
      if(isset($_POST['adminsubmit']))
      {
        $email=$_POST['email'];
        $ssn=$_POST['ssn'];
        $pass=$_POST['pass'];

 $conn = new mysqli("localhost", "root", "", "mydb");
    $quer = "select * from accinfo where email='$email'";
    $sql_result = mysqli_query($conn, $quer);
    if(mysqli_num_rows($sql_result) >0)
    {
      echo "<script> alert('account already exitst for this email')</script>";
    }
    else
    {
      $hashpass=md5($pass);
      $quer = "insert into accinfo(email,password,type,ssn) values('$email','$hashpass','a','$ssn')";
      $sql_result = mysqli_query($conn, $quer);
    }
      }

      ?>
      
<div class="internal"  style="display: none;" id="in-4">
        <div class="centering_addadmin">   
        <form  style="color:white;" method="post" action="admindash.php">
          <div class="admin_details">
            <h1 style="font-family: 'Ysabeau SC', sans-serif;"><u>Admin Details</u></h1>
          </div>
            <label for="username">Emain Id:</label>
            <input style="height:45px;width:455px;margin-bottom:10px;border-radius:5%;"type="email" id="username" name="email" placeholder="Enter Email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="pass" placeholder="Enter Password" required><br>

            
            <label for="ssn">Social Security Number:</label>
            <input type="text" id="ssn" name="ssn" placeholder="Enter Social Security Number" required><br><br>
            
            <input type="submit" value="Add Admin" name="adminsubmit">
        </form>
        </div>
      </div>

      