<?php
// session_start(); // Uncomment this if you need session management
session_start();
if (isset($_SESSION['email'])) {
  $email = $_SESSION['email']; // Retrieve the email from the session
} else {
  $email = "Email not available"; // Handle the case where the email is not set
}

// if (isset($_SESSION['departDate'])) {
//   $D_Date = $_SESSION['departDate']; // Retrieve the departdate from the session
// } else {
//   $departdate = "Depart date not available"; // Handle the case where the departdate is not set
// }
$busNumber = isset($_POST['bus_number']) ? $_POST['bus_number'] : 'Default Bus Number';
$busName = isset($_POST['busname']) ? $_POST['busname'] : 'ABC Bus';
$fromLocation = isset($_POST['from']) ? $_POST['from'] : 'City A';
$toLocation = isset($_POST['to']) ? $_POST['to'] : 'City B';
$departTime = isset($_POST['deptime']) ? $_POST['deptime'] : '9:00 AM';
$D_Date = isset($_POST['date']) ? $_POST['date'] : '2024-10-18';
$arrTime = isset($_POST['arrtime']) ? $_POST['arrtime'] : '12:00 PM';
$cost = isset($_POST['price']) ? $_POST['price'] : '';
$seats = isset($_POST['seats']) ? $_POST['seats'] : '';
$totalSeats = isset($_POST['totalSeats']) ? $_POST['totalSeats'] : '';
$totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : ''; 
$cost = $totalPrice/$totalSeats;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="customer.css">
</head>

<body>
  <main>
    <section class="booking-details">
      <div class="container">
        <div class="booking-column">
          <h2>Booked Details</h2>
          <ul>
            <li>Bus Name: <?php echo $busName; ?></li>
            <li>Route: From <?php echo $fromLocation; ?> to <?php echo $toLocation; ?></li>
            <li>Departure Time: <?php echo $departTime; ?></li>
            <li>Arrival Time: <?php echo $arrTime; ?></li>
            <li>Price: $<?php echo $totalPrice; ?></li>
            <li>Seats: <?php echo $seats; ?></li>
            <li>Total Seats: <?php echo $totalSeats; ?></li>
            <li>Date: <?php echo $D_Date; ?></li>
          </ul>
        </div>

        <div class="customer-column">
          <h2>Customer Information</h2>
          <form id="customer-form">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" id="age" name="age" placeholder="Enter your age" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            <button type="submit">Save Information</button>
          </form>
        </div>

        <div class="payment-column">
          <h2>Payment Method</h2>
          <div class="form-group">
            <select id="payment-method" name="payment-method" required>
              <option value="" disabled selected>Select Payment Method</option>
              <option value="credit-card">Credit Card</option>
              <option value="gpay">GPay</option>
              <option value="amazon-pay">Amazon Pay</option>
              <option value="whatsapp-pay">Whatsapp Pay</option>
            </select>
          </div>
          <button class="btn" id="proceed-btn">Proceed to Payment</button>
        </div>

        <div class="back-button">
          <a href="javascript:history.back()">Go Back</a>
        </div>
      </div>
    </section>
  </main>
  
  <div id="popup" class="popup">
    <span class="close-button">&times;</span>
    <h2 id="payment-method-heading">Payment Method</h2>

    <form id="credit-card-form" action="../payment/successful.php" method="POST">
    <div class="form-group">
        <label for="card-number">Card Number</label>
        <input type="text" id="card-number" name="card-number" placeholder="Enter your card number" required>
    </div>
    <div class="form-group">
        <label for="cardholder-name">Cardholder Name</label>
        <input type="text" id="cardholder-name" name="cardholder-name" placeholder="Enter the cardholder name" required>
    </div>
    <div class="form-group">
        <label for="expiry-date">Expiry Date</label>
        <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YYYY" required>
    </div>
    <div class="form-group">
        <label for="cvv">CVV</label>
        <input type="text" id="cvv" name="cvv" placeholder="Enter the CVV code" required>
    </div>
    <div class="form-group">
        <input type="submit" value="Pay Now" class="qr-btn">
    </div>
    </form>

    <div class="qr-code"></div>
    <form action="../payment/sucessfull.php">
      <button type="submit" class="qr-btn">Pay Now</button>
    </form>
  </div>

  <script>
    // Get the "Proceed to Payment" button element
    const proceedButton = document.getElementById('proceed-btn');
    const customerForm = document.getElementById('customer-form');

    // Get the popup element
    const popup = document.querySelector('.popup');

    // Get the close button element
    const closeButton = document.querySelector('.close-button');

    // Add event listener to the customer form submission
    customerForm.addEventListener('submit', (e) => {
      e.preventDefault(); // Prevent the form from submitting

      // Retrieve form values
      const name = document.getElementById('name').value;
      const age = document.getElementById('age').value;
      const phone = document.getElementById('phone').value;

      // Store in session storage
      sessionStorage.setItem('name', name);
      sessionStorage.setItem('age', age);
      sessionStorage.setItem('phone', phone);
      //storing php variables
      sessionStorage.setItem('busNumber', '<?php echo $busNumber; ?>');
      sessionStorage.setItem('email', '<?php echo $email; ?>');
      sessionStorage.setItem('busName', '<?php echo $busName; ?>');
      sessionStorage.setItem('from', '<?php echo $fromLocation; ?>');
      sessionStorage.setItem('to', '<?php echo $toLocation; ?>');
      sessionStorage.setItem('departTime', '<?php echo $departTime; ?>');
      sessionStorage.setItem('D_Date', '<?php echo $D_Date; ?>');
      sessionStorage.setItem('arrTime', '<?php echo $arrTime; ?>');
      sessionStorage.setItem('totalPrice', '<?php echo $totalPrice; ?>');
      sessionStorage.setItem('seats', '<?php echo $seats; ?>');
      sessionStorage.setItem('cost', '<?php echo $cost; ?>');
      sessionStorage.setItem('totalSeats', '<?php echo $totalSeats; ?>');

      alert('Customer information saved successfully!');
      // Optionally, you can redirect to another page or clear the form
    });

    // Add event listener to the button
    proceedButton.addEventListener('click', () => {
      const paymentMethod = document.getElementById('payment-method').value;

      // Show the popup based on the selected payment option
      if (paymentMethod === 'credit-card') {
        document.getElementById('payment-method-heading').textContent = 'Credit Card Details';
        document.querySelector('.qr-code').innerHTML = '';
        document.getElementById('credit-card-form').style.display = 'block';
        popup.classList.add('show');
      } else {
        // Generate QR code based on the selected payment method
        const qrCodeImage = generateQRCode(paymentMethod);

        if (qrCodeImage) {
          document.getElementById('payment-method-heading').textContent = paymentMethod.toUpperCase();
          const qrCodeContainer = document.querySelector('.qr-code');
          qrCodeContainer.innerHTML = '';
          qrCodeContainer.appendChild(qrCodeImage);
          document.getElementById('credit-card-form').style.display = 'none';
          popup.classList.add('show');
        } else {
          alert('Please select a valid payment method.');
        }
      }
    });

    // Add event listener to the close button
    closeButton.addEventListener('click', () => {
      popup.classList.remove('show');
    });

    // Close the popup when the form is submitted
    const paymentForm = document.querySelector('.popup form');
    paymentForm.addEventListener('submit', (e) => {
      e.preventDefault();
      popup.classList.remove('show');
      // Perform further actions here, such as submitting the payment details
      window.location.href = '../payment/sucessfull.php';
    });

    // Function to generate the QR code based on the selected payment method
    function generateQRCode(paymentMethod) {
      const qrCodeMap = {
        'gpay': '../images/gpay_qr_code.png',
        'amazon-pay': '../images/amazon_pay_qr_code.png',
        'whatsapp-pay': '../images/whatsapp_pay_qr_code.png',
      };

      if (qrCodeMap.hasOwnProperty(paymentMethod)) {
        const qrCodeImage = new Image();
        qrCodeImage.src = qrCodeMap[paymentMethod];
        return qrCodeImage;
      }

      return null;
    }
  </script>
</body>

</html>
