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
                            <input type="text" id="from" name="from" placeholder="Enter your starting point" required>
                        </div>
                        <div class="form-group">
                            <label for="to">To</label>
                            <input type="text" id="to" name="to" placeholder="Enter your destination" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <button type="submit">Search</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
