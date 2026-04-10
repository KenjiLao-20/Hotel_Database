<?php
include '../includes/db_connect.php';

$guest_id = $_GET['id'];

$sql = "DELETE FROM guests WHERE guest_id = $guest_id";

if ($conn->query($sql) === TRUE) {
    header("Location: read_guests.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>