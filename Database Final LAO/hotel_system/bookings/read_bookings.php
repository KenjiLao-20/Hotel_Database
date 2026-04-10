<?php
include '../includes/db_connect.php';

$sql = "SELECT b.*, g.first_name, g.last_name, r.room_number, r.room_type 
        FROM bookings b
        JOIN guests g ON b.guest_id = g.guest_id
        JOIN rooms r ON b.room_id = r.room_id
        ORDER BY b.check_in_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking List - Hotel System</title>
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
            <a href="read_bookings.php" class="nav-item active">📅 Bookings</a>
            <a href="../services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="../orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="../invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Booking List</h1>
            <p class="page-subtitle">Manage guest reservations</p>
            
            <div class="button-group">
                <a href="create_booking.php" class="btn btn-primary">📝 New Booking</a>
                <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Nights</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <?php
                            $statusClass = '';
                            if($row['booking_status'] == 'Active') $statusClass = 'booking-active';
                            elseif($row['booking_status'] == 'CheckedOut') $statusClass = 'booking-checkedout';
                            else $statusClass = 'booking-cancelled';
                        ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo $row['room_number'] . ' (' . $row['room_type'] . ')'; ?></td>
                            <td><?php echo $row['check_in_date']; ?></td>
                            <td><?php echo $row['check_out_date']; ?></td>
                            <td><?php echo $row['total_nights']; ?></td>
                            <td><span class="badge <?php echo $statusClass; ?>"><?php echo $row['booking_status']; ?></span></td>
                            <td>
                                <a href="update_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="delete_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-delete" onclick="return confirmDelete('Booking #<?php echo $row['booking_id']; ?>', <?php echo $row['booking_id']; ?>)">🗑️ Cancel</a>
                                <a href="../invoices/view_invoice.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn-view">📄 Invoice</a>
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