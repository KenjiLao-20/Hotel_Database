<?php
include 'includes/db_connect.php';

// Get statistics for dashboard
$total_guests = $conn->query("SELECT COUNT(*) as count FROM guests")->fetch_assoc()['count'];
$total_rooms = $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'Active'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM invoices WHERE payment_status = 'Paid'")->fetch_assoc()['total'];
$available_rooms = $conn->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'Available'")->fetch_assoc()['count'];
$occupied_rooms = $conn->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'Occupied'")->fetch_assoc()['count'];

$total_revenue = $total_revenue ? '$' . number_format($total_revenue, 2) : '$0.00';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Concierge & Billing System</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
            <a href="index.php" class="nav-item active">🏠 Dashboard</a>
            <a href="guests/read_guests.php" class="nav-item">👤 Guests</a>
            <a href="rooms/read_rooms.php" class="nav-item">🛏️ Rooms</a>
            <a href="bookings/read_bookings.php" class="nav-item">📅 Bookings</a>
            <a href="services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="invoices/view_invoice.php" class="nav-item">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome to your hotel management dashboard. Monitor key metrics and manage operations.</p>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $total_guests; ?></div>
                    <div class="stat-label">Total Guests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $total_rooms; ?></div>
                    <div class="stat-label">Total Rooms</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $total_bookings; ?></div>
                    <div class="stat-label">Active Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $total_revenue; ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $available_rooms; ?></div>
                    <div class="stat-label">Available Rooms</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-value"><?php echo $occupied_rooms; ?></div>
                    <div class="stat-label">Occupied Rooms</div>
                </div>
            </div>
            
            <!-- Menu Cards -->
            <div class="dashboard">
                <!-- Guests -->
                <div class="card">
                    <div class="card-header">
                        <h2>👤 Guests Management</h2>
                    </div>
                    <div class="card-body">
                        <a href="guests/create_guest.php" class="btn btn-primary">➕ Add New Guest</a>
                        <a href="guests/read_guests.php" class="btn btn-outline">📋 View All Guests</a>
                    </div>
                </div>
                
                <!-- Rooms -->
                <div class="card">
                    <div class="card-header">
                        <h2>🛏️ Rooms Management</h2>
                    </div>
                    <div class="card-body">
                        <a href="rooms/create_room.php" class="btn btn-primary">➕ Add New Room</a>
                        <a href="rooms/read_rooms.php" class="btn btn-outline">📋 View All Rooms</a>
                    </div>
                </div>
                
                <!-- Bookings -->
                <div class="card">
                    <div class="card-header">
                        <h2>📅 Bookings Management</h2>
                    </div>
                    <div class="card-body">
                        <a href="bookings/create_booking.php" class="btn btn-primary">📝 New Booking</a>
                        <a href="bookings/read_bookings.php" class="btn btn-outline">📋 View All Bookings</a>
                    </div>
                </div>
                
                <!-- Services -->
                <div class="card">
                    <div class="card-header">
                        <h2>🍽️ Services Menu</h2>
                    </div>
                    <div class="card-body">
                        <a href="services/create_service.php" class="btn btn-primary">➕ Add Service</a>
                        <a href="services/read_services.php" class="btn btn-outline">📋 View Menu</a>
                    </div>
                </div>
                
                <!-- Orders -->
                <div class="card">
                    <div class="card-header">
                        <h2>🛎️ Room Orders</h2>
                    </div>
                    <div class="card-body">
                        <a href="orders/create_order.php" class="btn btn-primary">📝 Place Order</a>
                    </div>
                </div>
                
                <!-- Invoices -->
                <div class="card">
                    <div class="card-header">
                        <h2>💰 Invoices & Billing</h2>
                    </div>
                    <div class="card-body">
                        <a href="invoices/view_invoice.php" class="btn btn-primary">📄 View Invoices</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>