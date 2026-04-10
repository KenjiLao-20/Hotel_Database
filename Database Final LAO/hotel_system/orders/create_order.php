<?php
include '../includes/db_connect.php';

// Get active bookings for dropdown
$bookings = $conn->query("
    SELECT b.booking_id, g.first_name, g.last_name, r.room_number 
    FROM bookings b
    JOIN guests g ON b.guest_id = g.guest_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE b.booking_status = 'Active'
");

// Get services for dropdown
$services = $conn->query("SELECT service_id, service_name, category, price FROM services ORDER BY category");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $service_id = $_POST['service_id'];
    $quantity = $_POST['quantity'];
    
    $sql = "INSERT INTO room_orders (booking_id, service_id, quantity) 
            VALUES ('$booking_id', '$service_id', '$quantity')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Order placed successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Display recent orders
$recent_orders = $conn->query("
    SELECT ro.*, g.first_name, g.last_name, s.service_name, s.price 
    FROM room_orders ro
    JOIN bookings b ON ro.booking_id = b.booking_id
    JOIN guests g ON b.guest_id = g.guest_id
    JOIN services s ON ro.service_id = s.service_id
    ORDER BY ro.order_datetime DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Orders - Hotel System</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../script.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Hotel Concierge & Billing System</h1>
            <p>Enterprise Hotel Management Platform</p>
        </div>
        
        <!-- Navigation -->
        <div class="nav-menu">
            <a href="../index.php" class="nav-item">🏠 Dashboard</a>
            <a href="../guests/read_guests.php" class="nav-item">👤 Guests</a>
            <a href="../rooms/read_rooms.php" class="nav-item">🛏️ Rooms</a>
            <a href="../bookings/read_bookings.php" class="nav-item">📅 Bookings</a>
            <a href="../services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="create_order.php" class="nav-item active">🛎️ Room Orders</a>
            <a href="../invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Place Room Service Order</h1>
            <p class="page-subtitle">Order services for active guest bookings</p>
            
            <div class="button-group">
                <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <?php if(isset($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" onsubmit="return validateOrderForm()">
                    <div class="form-group">
                        <label>Select Booking (Guest & Room):</label>
                        <select name="booking_id" id="booking_id" required>
                            <option value="">Select Active Booking</option>
                            <?php while($row = $bookings->fetch_assoc()): ?>
                            <option value="<?php echo $row['booking_id']; ?>">
                                Booking #<?php echo $row['booking_id']; ?> - <?php echo $row['first_name'] . ' ' . $row['last_name']; ?> (Room <?php echo $row['room_number']; ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Select Service:</label>
                        <select name="service_id" id="service_id" required>
                            <option value="">Select Service</option>
                            <?php while($row = $services->fetch_assoc()): ?>
                            <option value="<?php echo $row['service_id']; ?>">
                                <?php echo $row['service_name']; ?> (<?php echo $row['category']; ?>) - $<?php echo number_format($row['price'], 2); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
            </div>
            
            <h2 class="page-title" style="margin-top: 40px; font-size: 22px;">Recent Orders</h2>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Guest</th>
                            <th>Service</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Order Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo $row['service_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></td>
                            <td><?php echo $row['order_datetime']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>