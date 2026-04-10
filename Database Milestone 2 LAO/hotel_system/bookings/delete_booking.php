<?php
include '../includes/db_connect.php';

$booking_id = $_GET['id'];

// Get room_id before deleting
$sql = "SELECT room_id FROM bookings WHERE booking_id = $booking_id";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

if ($booking) {
    $room_id = $booking['room_id'];
    
    // Delete the booking
    $delete_sql = "DELETE FROM bookings WHERE booking_id = $booking_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        // Update room status back to Available
        $conn->query("UPDATE rooms SET status = 'Available' WHERE room_id = $room_id");
        header("Location: read_bookings.php");
    } else {
        header("Location: read_bookings.php?error=" . $conn->error);
    }
} else {
    header("Location: read_bookings.php");
}
?>