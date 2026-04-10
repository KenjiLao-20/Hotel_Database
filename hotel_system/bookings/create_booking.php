<?php
include '../includes/db_connect.php';

// Get guests and rooms for dropdowns
$guests = $conn->query("SELECT guest_id, first_name, last_name FROM guests");
$rooms = $conn->query("SELECT room_id, room_number, price_per_night FROM rooms WHERE status = 'Available'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in_date'];
    $check_out = $_POST['check_out_date'];
    $status = $_POST['booking_status'];
    
    $sql = "INSERT INTO bookings (guest_id, room_id, check_in_date, check_out_date, booking_status) 
            VALUES ('$guest_id', '$room_id', '$check_in', '$check_out', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        // Update room status to Occupied
        $conn->query("UPDATE rooms SET status = 'Occupied' WHERE room_id = $room_id");
        $success = "Booking created successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Booking</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Create New Booking</h1>
        <a href="read_bookings.php" class="btn btn-secondary">← Back to Bookings</a>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Guest:</label>
                <select name="guest_id" required>
                    <option value="">Select Guest</option>
                    <?php while($row = $guests->fetch_assoc()): ?>
                    <option value="<?php echo $row['guest_id']; ?>">
                        <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Room:</label>
                <select name="room_id" required>
                    <option value="">Select Room</option>
                    <?php while($row = $rooms->fetch_assoc()): ?>
                    <option value="<?php echo $row['room_id']; ?>">
                        Room <?php echo $row['room_number']; ?> - $<?php echo $row['price_per_night']; ?>/night
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Check-In Date:</label>
                <input type="date" name="check_in_date" required>
            </div>
            
            <div class="form-group">
                <label>Check-Out Date:</label>
                <input type="date" name="check_out_date" required>
            </div>
            
            <div class="form-group">
                <label>Booking Status:</label>
                <select name="booking_status">
                    <option value="Active">Active</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Booking</button>
        </form>
    </div>
</body>
</html>