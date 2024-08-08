<html>
    <head>
    <style>
    .add-employee-button {
        background-color: blue;
        color: white;
        border-radius: 5%;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        margin-left: 10px;
        margin-top:10px;
        margin-bottom: 20px;
    }
</style>
    </head>
    <body>


<?php
if (isset($_POST['delete'])) {
    $conn = new mysqli("localhost", "root", "", "mydb");
    $deleteid = $_POST['deleteid'];
    mysqli_query($conn, "DELETE FROM empinfo WHERE empid='$deleteid'");

    echo "<script> location.href='admindash.php' </script>";
}

?>

<div class="internal" style="display: none;" id="in-6">
<button class="add-employee-button" onclick="showAddEmployeePopup()">Add Employee</button>
    <div id="add-employee" class="section">
        <table>
            <thead>
                <tr>
                    <th>Sl. No</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="employee-table-body">
                <?php
                $conn = new mysqli("localhost", "root", "", "mydb");
                $quer = "SELECT * FROM empinfo;";
                $result = mysqli_query($conn, $quer);
                if ($result != null) {
                    $count = mysqli_num_rows($result);
                    $n = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $n++;
                        $empid = $row['empid'];
                        $empname = $row['empname'];
                        $email = $row['email'];
                        $phoneno = $row['phno'];
                ?>
                        <form action="admindash.php" method="POST">
                            <input type="text" name="deleteid" style="display:none !important" value='<?php echo "$empid" ?>'>
                            <tr style="color: rgb(0, 0, 0);">
                                <td><?php echo "$n" ?></td>
                                <td><?php echo "$empid" ?></td>
                                <td><?php echo "$empname" ?></td>
                                <td><?php echo "$email" ?> </td>
                                <td><?php echo "$phoneno" ?></td>
                                <td>
                                    <button type="button" name="edit" onclick="showEditEmployeePopup(this)">Edit</button>
                                    <button name="delete" type="submit">Delete</button>
                                </td>
                            </tr>
                        </form>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
if (isset($_POST['addemployee'])) {
    $conn = new mysqli("localhost", "root", "", "mydb");
    $empname = $_POST['empname'];
    $email = $_POST['email'];
    $phoneno = $_POST['phoneno'];
    $empid = $_POST['empid'];

    $quer = "INSERT INTO empinfo (empid, empname, phno, email) VALUES ('$empid', '$empname', '$phoneno', '$email')";
    $sql_result = mysqli_query($conn, $quer);

    if (!$sql_result) {
        echo "ERROR while adding emp details: " . mysqli_error($conn);
    } else {
        echo "Employee details added successfully.";
    }
}
?>

   

<div class="popup" id="add-employee-popup">
    <div class="popup-header">
        <h2 style="display: inline-block;">Add Employee Details</h2>
        <button class="close-button" onclick="hideAddEmployeePopup()">X</button>
    </div>
    <form class="popup-form" action="admindash.php" method="POST">
        <label for="emp-id">Employee ID:</label>
        <input style="width: 150px; border-width: 2px; border-radius: 2px;border-color: black;" name="empid" type="text" id="add-emp-id-input" placeholder="Enter employee ID" required>

        <label for="emp-name">Employee Name:</label>
        <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="empname" type="text" id="add-emp-name-input" placeholder="Enter employee name" required>

        <label for="emp-email">Email:</label>
        <input style="height:25px;width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="email" type="email" id="add-emp-email-input" placeholder="Enter email" required>

        <label for="emp-phone">Phone Number:</label>
        <input style="width: 150px; height: 40px; " name="phoneno" type="integer" id="add-emp-phone-input" placeholder="Enter phone number" required>

        <input style="width: 90px;" type="submit" value="Add" name="addemployee">
    </form>
</div>


 

    <div class="popup" id="edit-employee-popup">
        <div class="popup-header">
            <h2 style="display: inline-block;">Edit Employee Details</h2>
            <button class="close-button" onclick="hideEditEmployeePopup()">X</button>
        </div>
        <form class="popup-form" action="admindash.php" method="POST">
            <input type="text" name="empid" id="empid" style="display:none !important" value="">
            <input type="hidden" id="edit-emp-row-index" value="">
            <label for="emp-name">Employee Name:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="empname" type="text" id="edit-emp-name-input" placeholder="Enter employee name" required>

            <label for="emp-email">Email:</label>
            <input style="width: 290px; border-width: 2px; border-radius: 2px;border-color: black;" name="email" type="email" id="edit-emp-email-input" placeholder="Enter email" required>

            <label for="emp-phone">Phone Number:</label>
            <input style="width: 150px; height: 40px; " name="phoneno" type="integer" id="edit-emp-phone-input" placeholder="Enter phone number" required>

            <input style="width: 90px;" type="submit" value="Save" name="editsaveemp" onclick="saveEmployeeDetails(event)">

        </form>
    </div>

    <div class="overlay" id="overlay"></div>
    <script>
        var employeeCount = 1; // Counter to track the number of employees

        function showAddEmployeePopup() {
            var addEmployeePopup = document.getElementById("add-employee-popup");
            var overlay = document.getElementById("overlay");
            addEmployeePopup.style.display = "block";
            overlay.style.display = "block";
        }

        function hideAddEmployeePopup() {
            var addEmployeePopup = document.getElementById("add-employee-popup");
            var overlay = document.getElementById("overlay");
            addEmployeePopup.style.display = "none";
            overlay.style.display = "none";
        }

        function showEditEmployeePopup(link) {
            var row = link.parentNode.parentNode;
            var rowIndex = Array.from(row.parentNode.children).indexOf(row);
            var empid = row.children[1].textContent;
            var empName = row.children[2].textContent;
            var empEmail = row.children[3].textContent;
            var empPhone = row.children[4].textContent;

            var editEmployeePopup = document.getElementById("edit-employee-popup");
            var overlay = document.getElementById("overlay");
            editEmployeePopup.style.display = "block";
            overlay.style.display = "block";
            document.getElementById('empid').value = empid;
            document.getElementById("edit-emp-row-index").value = rowIndex;
            document.getElementById("edit-emp-name-input").value = empName;
            document.getElementById("edit-emp-email-input").value = empEmail;
            document.getElementById("edit-emp-phone-input").value = empPhone;
        }

        function hideEditEmployeePopup() {
            var editEmployeePopup = document.getElementById("edit-employee-popup");
            var overlay = document.getElementById("overlay");
            editEmployeePopup.style.display = "none";
            overlay.style.display = "none";
        }

        function saveEmployeeDetails(event) {

            var rowIndex = document.getElementById("edit-emp-row-index").value;
            var row = document.getElementById("employee-table-body").children[rowIndex];

            var empName = document.getElementById("edit-emp-name-input").value;
            var empEmail = document.getElementById("edit-emp-email-input").value;
            var empPhone = document.getElementById("edit-emp-phone-input").value;

            row.children[2].textContent = empName;
            row.children[3].textContent = empEmail;
            row.children[4].textContent = empPhone;

            // Close the popup
            hideEditEmployeePopup();
        }

        function deleteEmployee(link) {
            var row = link.parentNode.parentNode;
            row.parentNode.removeChild(row);

            // Reset serial numbers after deleting a row
            var rows = document.querySelectorAll("#employee-table-body tr");
            for (var i = 0; i < rows.length; i++) {
                rows[i].children[0].textContent = i + 1;
            }
        }
    </script>
</div>
    </body>
    </html>
