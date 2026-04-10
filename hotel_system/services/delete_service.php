<?php
include '../includes/db_connect.php';

$service_id = $_GET['id'];

// Check if service has any orders
$check_sql = "SELECT COUNT(*) as count FROM room_orders WHERE service_id = $service_id";
$check_result = $conn->query($check_sql);
$check = $check_result->fetch_assoc();

if ($check['count'] > 0) {
    header("Location: read_services.php?error=Cannot delete service with existing orders");
    exit();
}

$sql = "DELETE FROM services WHERE service_id = $service_id";

if ($conn->query($sql) === TRUE) {
    header("Location: read_services.php");
} else {
    header("Location: read_services.php?error=" . $conn->error);
}
?>