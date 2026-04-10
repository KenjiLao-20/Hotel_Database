<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM rooms ORDER BY room_number";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List - Hotel System</title>
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
            <a href="read_rooms.php" class="nav-item active">🛏️ Rooms</a>
            <a href="../bookings/read_bookings.php" class="nav-item">📅 Bookings</a>
            <a href="../services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="../orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="../invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Room List</h1>
            <p class="page-subtitle">Manage hotel rooms and their status</p>
            
            <div class="button-group">
                <a href="create_room.php" class="btn btn-primary">➕ Add New Room</a>
                <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Price/Night</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <?php
                            $statusClass = '';
                            if($row['status'] == 'Available') $statusClass = 'badge-success';
                            elseif($row['status'] == 'Occupied') $statusClass = 'badge-danger';
                            elseif($row['status'] == 'Maintenance') $statusClass = 'badge-warning';
                            else $statusClass = 'badge-info';
                        ?>
                        <tr>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['room_number']; ?></td>
                            <td><?php echo $row['room_type']; ?></td>
                            <td>$<?php echo number_format($row['price_per_night'], 2); ?></td>
                            <td><span class="badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                            <td>
                                <a href="update_room.php?id=<?php echo $row['room_id']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="delete_room.php?id=<?php echo $row['room_id']; ?>" class="btn-delete" onclick="return confirmDelete('Room <?php echo $row['room_number']; ?>', <?php echo $row['room_id']; ?>)">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>