<?php
include '../includes/db_connect.php';

$room_id = $_GET['id'];

// Check if room has any bookings
$check_sql = "SELECT COUNT(*) as count FROM bookings WHERE room_id = $room_id";
$check_result = $conn->query($check_sql);
$check = $check_result->fetch_assoc();

if ($check['count'] > 0) {
    // Room has bookings, cannot delete
    header("Location: read_rooms.php?error=Cannot delete room with existing bookings");
    exit();
}

$sql = "DELETE FROM rooms WHERE room_id = $room_id";

if ($conn->query($sql) === TRUE) {
    header("Location: read_rooms.php");
} else {
    header("Location: read_rooms.php?error=" . $conn->error);
}
?>