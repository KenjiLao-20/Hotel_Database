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
<html>
<head>
    <title>Booking List</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Booking List</h1>
        <a href="create_booking.php" class="btn btn-primary">+ New Booking</a>
        <a href="../index.php" class="btn btn-secondary">← Back to Dashboard</a>
        
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
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['room_number'] . ' (' . $row['room_type'] . ')'; ?></td>
                    <td><?php echo $row['check_in_date']; ?></td>
                    <td><?php echo $row['check_out_date']; ?></td>
                    <td><?php echo $row['total_nights']; ?></td>
                    <td><?php echo $row['booking_status']; ?></td>
                    <td>
                        <a href="update_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-delete" onclick="return confirm('Cancel this booking?')">Cancel</a>
                        <a href="../invoices/view_invoice.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn-view">Invoice</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>