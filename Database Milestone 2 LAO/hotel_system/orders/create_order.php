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
<html>
<head>
    <title>Place Room Order</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🛎️ Place Room Service Order</h1>
        <a href="../index.php" class="btn btn-secondary">← Back to Dashboard</a>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Select Booking (Guest & Room):</label>
                <select name="booking_id" required>
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
                <select name="service_id" required>
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
                <input type="number" name="quantity" value="1" min="1" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
        
        <h2 style="margin-top: 40px;">Recent Orders</h2>
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
</body>
</html>