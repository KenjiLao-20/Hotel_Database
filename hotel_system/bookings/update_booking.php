<?php
include '../includes/db_connect.php';

$booking_id = $_GET['id'];

// Fetch current booking data with joins
$sql = "SELECT b.*, g.first_name, g.last_name, r.room_number 
        FROM bookings b
        JOIN guests g ON b.guest_id = g.guest_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.booking_id = $booking_id";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

if (!$booking) {
    header("Location: read_bookings.php");
    exit();
}

// Get guests and rooms for dropdowns
$guests = $conn->query("SELECT guest_id, first_name, last_name FROM guests");
$rooms = $conn->query("SELECT room_id, room_number, price_per_night FROM rooms");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $booking_status = $_POST['booking_status'];
    
    // Get old room ID to update status later
    $old_room_id = $booking['room_id'];
    
    $sql = "UPDATE bookings SET 
            guest_id = '$guest_id',
            room_id = '$room_id',
            check_in_date = '$check_in_date',
            check_out_date = '$check_out_date',
            booking_status = '$booking_status'
            WHERE booking_id = $booking_id";
    
    if ($conn->query($sql) === TRUE) {
        // Update room statuses
        if ($booking_status == 'CheckedOut' || $booking_status == 'Cancelled') {
            $conn->query("UPDATE rooms SET status = 'Available' WHERE room_id = $room_id");
        } elseif ($booking_status == 'Active') {
            $conn->query("UPDATE rooms SET status = 'Occupied' WHERE room_id = $room_id");
        }
        
        // If room changed, update old room status
        if ($old_room_id != $room_id) {
            $conn->query("UPDATE rooms SET status = 'Available' WHERE room_id = $old_room_id");
        }
        
        header("Location: read_bookings.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Booking</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Update Booking #<?php echo $booking_id; ?></h1>
        <a href="read_bookings.php" class="btn btn-secondary">← Back to Bookings</a>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Guest:</label>
                <select name="guest_id" required>
                    <?php while($row = $guests->fetch_assoc()): ?>
                    <option value="<?php echo $row['guest_id']; ?>" <?php echo ($row['guest_id'] == $booking['guest_id']) ? 'selected' : ''; ?>>
                        <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Room:</label>
                <select name="room_id" required>
                    <?php 
                    $rooms = $conn->query("SELECT room_id, room_number, price_per_night FROM rooms");
                    while($row = $rooms->fetch_assoc()): 
                    ?>
                    <option value="<?php echo $row['room_id']; ?>" <?php echo ($row['room_id'] == $booking['room_id']) ? 'selected' : ''; ?>>
                        Room <?php echo $row['room_number']; ?> - $<?php echo $row['price_per_night']; ?>/night
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Check-In Date:</label>
                <input type="date" name="check_in_date" value="<?php echo $booking['check_in_date']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Check-Out Date:</label>
                <input type="date" name="check_out_date" value="<?php echo $booking['check_out_date']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Booking Status:</label>
                <select name="booking_status">
                    <option value="Active" <?php echo ($booking['booking_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="CheckedOut" <?php echo ($booking['booking_status'] == 'CheckedOut') ? 'selected' : ''; ?>>Checked Out</option>
                    <option value="Cancelled" <?php echo ($booking['booking_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Booking</button>
        </form>
    </div>
</body>
</html>