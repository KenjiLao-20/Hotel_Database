<?php
include '../includes/db_connect.php';

$room_id = $_GET['id'];

// Fetch current data
$sql = "SELECT * FROM rooms WHERE room_id = $room_id";
$result = $conn->query($sql);
$room = $result->fetch_assoc();

if (!$room) {
    header("Location: read_rooms.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];
    
    $sql = "UPDATE rooms SET 
            room_number = '$room_number',
            room_type = '$room_type',
            price_per_night = '$price_per_night',
            status = '$status'
            WHERE room_id = $room_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: read_rooms.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Room</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Update Room</h1>
        <a href="read_rooms.php" class="btn btn-secondary">← Back to Rooms</a>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" value="<?php echo $room['room_number']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Room Type:</label>
                <select name="room_type" required>
                    <option value="Standard" <?php echo ($room['room_type'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                    <option value="Deluxe" <?php echo ($room['room_type'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                    <option value="Suite" <?php echo ($room['room_type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                    <option value="Presidential" <?php echo ($room['room_type'] == 'Presidential') ? 'selected' : ''; ?>>Presidential</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price Per Night:</label>
                <input type="number" step="0.01" name="price_per_night" value="<?php echo $room['price_per_night']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="Available" <?php echo ($room['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                    <option value="Occupied" <?php echo ($room['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                    <option value="Maintenance" <?php echo ($room['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                    <option value="Reserved" <?php echo ($room['status'] == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Room</button>
        </form>
    </div>
</body>
</html>