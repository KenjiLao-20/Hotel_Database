<?php
include '../includes/db_connect.php';

$guest_id = $_GET['id'];

$sql = "SELECT * FROM guests WHERE guest_id = $guest_id";
$result = $conn->query($sql);
$guest = $result->fetch_assoc();

if (!$guest) {
    header("Location: read_guests.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $sql = "UPDATE guests SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email = '$email',
            phone = '$phone',
            address = '$address'
            WHERE guest_id = $guest_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: read_guests.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Guest - Hotel System</title>
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
            <a href="read_guests.php" class="nav-item active">👤 Guests</a>
            <a href="../rooms/read_rooms.php" class="nav-item">🛏️ Rooms</a>
            <a href="../bookings/read_bookings.php" class="nav-item">📅 Bookings</a>
            <a href="../services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="../orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="../invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Update Guest</h1>
            <p class="page-subtitle">Edit guest information below</p>
            
            <?php if(isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="button-group">
                <a href="read_guests.php" class="btn btn-outline">← Back to Guests</a>
            </div>
            
            <div class="form-container">
                <form method="POST" onsubmit="return validateGuestForm()">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo $guest['first_name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo $guest['last_name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo $guest['email']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" id="phone" value="<?php echo $guest['phone']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Address:</label>
                        <textarea name="address" id="address" rows="3"><?php echo $guest['address']; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Guest</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>