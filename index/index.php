<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d22e378a1e.js" crossorigin="anonymous"></script>
    <title>Bus Booking Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <main>
        <div class="full">
        <section class="search">
            <div class="container">
                <h1>Find Your Bus</h1>
                <form method="post" action="bus_details.php">
                    <div class="form-group">
                        <label for="from">From</label>
                        <input type="text" id="from" name="from" placeholder="Enter your starting point">
                    </div>
                    <div class="form-group">
                        <label for="to">To</label>
                        <input type="text" id="to" name="to" placeholder="Enter your destination">
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                       <!-- Modify the "date" input field -->
                       <input type="date" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>">
                    </div>
                    <button type="submit">Search</button>
                </form>
            </div>
        </section>
    </div>
    </main>
</body>
</html>
