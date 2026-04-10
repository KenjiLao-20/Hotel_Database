<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];
    
    $sql = "INSERT INTO rooms (room_number, room_type, price_per_night, status) 
            VALUES ('$room_number', '$room_type', '$price_per_night', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Room added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Room</h1>
        <a href="read_rooms.php" class="btn btn-secondary">← Back to Rooms</a>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" required>
            </div>
            
            <div class="form-group">
                <label>Room Type:</label>
                <select name="room_type" required>
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                    <option value="Presidential">Presidential</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price Per Night:</label>
                <input type="number" step="0.01" name="price_per_night" required>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Reserved">Reserved</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Room</button>
        </form>
    </div>
</body>
</html>