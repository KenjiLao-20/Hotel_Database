<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM guests ORDER BY guest_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest List - Hotel System</title>
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
            <h1 class="page-title">Guest List</h1>
            <p class="page-subtitle">Manage and view all registered hotel guests</p>
            
            <div class="button-group">
                <a href="create_guest.php" class="btn btn-primary">➕ Add New Guest</a>
                <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['guest_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="update_guest.php?id=<?php echo $row['guest_id']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="delete_guest.php?id=<?php echo $row['guest_id']; ?>" class="btn-delete" onclick="return confirmDelete('<?php echo addslashes($row['first_name']); ?>', <?php echo $row['guest_id']; ?>)">🗑️ Delete</a>
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