<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM services ORDER BY category, service_name";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Menu - Hotel System</title>
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
            <a href="read_services.php" class="nav-item active">🍽️ Services</a>
            <a href="../orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="../invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Service Menu</h1>
            <p class="page-subtitle">Manage hotel services and pricing</p>
            
            <div class="button-group">
                <a href="create_service.php" class="btn btn-primary">➕ Add New Service</a>
                <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['service_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><span class="badge badge-info"><?php echo $row['category']; ?></span></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <a href="update_service.php?id=<?php echo $row['service_id']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="delete_service.php?id=<?php echo $row['service_id']; ?>" class="btn-delete" onclick="return confirmDelete('<?php echo addslashes($row['service_name']); ?>', <?php echo $row['service_id']; ?>)">🗑️ Delete</a>
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