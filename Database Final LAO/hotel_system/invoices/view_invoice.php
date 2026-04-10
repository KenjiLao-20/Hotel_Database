<?php
include '../includes/db_connect.php';

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : null;

// If no booking_id specified, show all invoices
if (!$booking_id) {
    $all_invoices = $conn->query("
        SELECT i.*, g.first_name, g.last_name, r.room_number, b.check_in_date, b.check_out_date
        FROM invoices i
        JOIN bookings b ON i.booking_id = b.booking_id
        JOIN guests g ON b.guest_id = g.guest_id
        JOIN rooms r ON b.room_id = r.room_id
        ORDER BY i.invoice_date DESC
    ");
    $show_all = true;
} else {
    // Get invoice details for specific booking using the consolidated_charges view
    $invoice = $conn->query("SELECT * FROM consolidated_charges WHERE booking_id = $booking_id")->fetch_assoc();
    
    // Get all room orders for this booking
    $orders = $conn->query("
        SELECT ro.*, s.service_name, s.price 
        FROM room_orders ro
        JOIN services s ON ro.service_id = s.service_id
        WHERE ro.booking_id = $booking_id
    ");
    
    // Check if invoice already exists
    $existing_invoice = $conn->query("SELECT * FROM invoices WHERE booking_id = $booking_id")->fetch_assoc();
    $show_all = false;
}

// Handle manual invoice generation
if (isset($_POST['generate_invoice']) && isset($_POST['booking_id'])) {
    $gen_booking_id = $_POST['booking_id'];
    $conn->query("CALL CheckoutGuest($gen_booking_id)");
    header("Location: view_invoice.php?booking_id=$gen_booking_id");
    exit();
}

// Handle payment update
if (isset($_POST['update_payment']) && isset($_POST['invoice_id'])) {
    $invoice_id = $_POST['invoice_id'];
    $payment_status = $_POST['payment_status'];
    $conn->query("UPDATE invoices SET payment_status = '$payment_status' WHERE invoice_id = $invoice_id");
    header("Location: view_invoice.php" . (isset($_GET['booking_id']) ? "?booking_id=" . $_GET['booking_id'] : ""));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices & Billing - Hotel System</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../script.js"></script>
    <style>
        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 28px;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .invoice-header p {
            color: #64748b;
        }
        .invoice-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #0f172a;
        }
        .total-row {
            font-weight: bold;
            background: #f1f5f9;
        }
        .print-btn {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 10px;
            font-weight: 500;
        }
        .print-btn:hover {
            background: #059669;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .container {
                background: white;
                margin: 0;
                padding: 0;
            }
            .invoice-box {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header no-print">
            <h1>Hotel Concierge & Billing System</h1>
            <p>Enterprise Hotel Management Platform</p>
        </div>
        
        <!-- Navigation - no-print -->
        <div class="nav-menu no-print">
            <a href="../index.php" class="nav-item">🏠 Dashboard</a>
            <a href="../guests/read_guests.php" class="nav-item">👤 Guests</a>
            <a href="../rooms/read_rooms.php" class="nav-item">🛏️ Rooms</a>
            <a href="../bookings/read_bookings.php" class="nav-item">📅 Bookings</a>
            <a href="../services/read_services.php" class="nav-item">🍽️ Services</a>
            <a href="../orders/create_order.php" class="nav-item">🛎️ Room Orders</a>
            <a href="view_invoice.php" class="nav-item active">💰 Invoices</a>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="no-print">
                <h1 class="page-title">Invoices & Billing System</h1>
                <p class="page-subtitle">Manage guest invoices and payments</p>
                
                <div class="button-group">
                    <a href="../index.php" class="btn btn-outline">← Back to Dashboard</a>
                    <button onclick="window.print()" class="print-btn">🖨️ Print Invoice</button>
                </div>
            </div>
            
            <?php if($show_all): ?>
                <!-- Show all invoices summary -->
                <h2 class="page-title" style="font-size: 22px; margin-top: 20px;">All Invoices</h2>
                
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Guest Name</th>
                                <th>Room</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Invoice Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $all_invoices->fetch_assoc()): ?>
                            <?php $payClass = ($row['payment_status'] == 'Paid') ? 'badge-success' : (($row['payment_status'] == 'Unpaid') ? 'badge-danger' : 'badge-warning'); ?>
                            <tr>
                                <td>#<?php echo $row['invoice_id']; ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td>Room <?php echo $row['room_number']; ?></td>
                                <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td><span class="badge <?php echo $payClass; ?>"><?php echo $row['payment_status']; ?></span></td>
                                <td><?php echo $row['invoice_date']; ?></td>
                                <td><a href="view_invoice.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn-view">View Invoice</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Form to generate invoice for active booking -->
                <h2 class="page-title" style="font-size: 22px; margin-top: 40px;">Generate New Invoice</h2>
                
                <div class="form-container">
                    <form method="POST">
                        <div class="form-group">
                            <label>Select Active Booking to Generate Invoice:</label>
                            <select name="booking_id" required>
                                <option value="">Select Booking</option>
                                <?php
                                $active_bookings = $conn->query("
                                    SELECT b.booking_id, g.first_name, g.last_name, r.room_number
                                    FROM bookings b
                                    JOIN guests g ON b.guest_id = g.guest_id
                                    JOIN rooms r ON b.room_id = r.room_id
                                    WHERE b.booking_status = 'Active'
                                ");
                                while($row = $active_bookings->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['booking_id']; ?>">
                                    Booking #<?php echo $row['booking_id']; ?> - <?php echo $row['first_name'] . ' ' . $row['last_name']; ?> (Room <?php echo $row['room_number']; ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" name="generate_invoice" class="btn btn-primary">Generate Invoice & Checkout</button>
                    </form>
                </div>
                
            <?php else: ?>
                <!-- Show single invoice -->
                <div class="invoice-box">
                    <div class="invoice-header">
                        <h1>🏨 HOTEL CONCIERGE</h1>
                        <h2>TAX INVOICE</h2>
                        <p>123 Hotel Street, City, Country | Tel: +123 456 7890</p>
                    </div>
                    
                    <?php if($invoice): ?>
                    <div class="invoice-details">
                        <table style="width:100%">
                            <tr>
                                <td><strong>Invoice #:</strong> <?php echo isset($existing_invoice['invoice_id']) ? $existing_invoice['invoice_id'] : 'Pending'; ?></td>
                                <td><strong>Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Guest Name:</strong> <?php echo $invoice['first_name'] . ' ' . $invoice['last_name']; ?></td>
                                <td><strong>Room:</strong> <?php echo $invoice['room_number'] . ' (' . $invoice['room_type'] . ')'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Check-In:</strong> <?php echo $invoice['check_in_date']; ?></td>
                                <td><strong>Check-Out:</strong> <?php echo $invoice['check_out_date']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nights:</strong> <?php echo $invoice['total_nights']; ?></td>
                                <td><strong>Rate/Night:</strong> $<?php echo number_format($invoice['price_per_night'], 2); ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <h3>Room Charges</h3>
                    <table class="invoice-table">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td><?php echo $invoice['total_nights']; ?> nights x $<?php echo number_format($invoice['price_per_night'], 2); ?></td>
                            <td>$<?php echo number_format($invoice['room_total'], 2); ?></td>
                        </tr>
                    </table>
                    
                    <?php if($orders && $orders->num_rows > 0): ?>
                    <h3>Service Charges</h3>
                    <table class="invoice-table">
                        <tr>
                            <th>Service</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                        <?php while($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['service_name']; ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>$<?php echo number_format($order['price'], 2); ?></td>
                            <td>$<?php echo number_format($order['quantity'] * $order['price'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                    <?php else: ?>
                    <p>No service charges for this booking.</p>
                    <?php endif; ?>
                    
                    <h3>Summary</h3>
                    <table class="invoice-table">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>Room Charges</td>
                            <td>$<?php echo number_format($invoice['room_total'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Service Charges</td>
                            <td>$<?php echo number_format($invoice['service_total'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Tax (12%)</td>
                            <td>$<?php echo number_format($invoice['tax'], 2); ?></td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>GRAND TOTAL</strong></td>
                            <td><strong>$<?php echo number_format($invoice['grand_total'], 2); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Payment Status</td>
                            <td>
                                <span class="badge <?php echo isset($existing_invoice['payment_status']) ? (($existing_invoice['payment_status'] == 'Paid') ? 'badge-success' : 'badge-danger') : 'badge-warning'; ?>">
                                    <?php echo isset($existing_invoice['payment_status']) ? $existing_invoice['payment_status'] : 'Not Generated Yet'; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                    
                    <?php if(!isset($existing_invoice)): ?>
                    <form method="POST" class="no-print" style="margin-top: 20px;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                        <button type="submit" name="generate_invoice" class="btn btn-primary">Generate Invoice & Checkout</button>
                    </form>
                    <?php else: ?>
                    <form method="POST" class="no-print" style="margin-top: 20px;">
                        <input type="hidden" name="invoice_id" value="<?php echo $existing_invoice['invoice_id']; ?>">
                        <div class="form-group">
                            <label>Update Payment Status:</label>
                            <select name="payment_status" class="form-control">
                                <option value="Unpaid" <?php echo ($existing_invoice['payment_status'] == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                                <option value="PartiallyPaid" <?php echo ($existing_invoice['payment_status'] == 'PartiallyPaid') ? 'selected' : ''; ?>>Partially Paid</option>
                                <option value="Paid" <?php echo ($existing_invoice['payment_status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                            </select>
                        </div>
                        <button type="submit" name="update_payment" class="btn btn-primary">Update Payment Status</button>
                    </form>
                    <?php endif; ?>
                    
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <p><strong>Thank you for staying with us!</strong></p>
                    </div>
                    
                    <?php else: ?>
                    <div class="alert error">
                        <p>No booking found with ID: <?php echo $booking_id; ?></p>
                        <a href="view_invoice.php" class="btn btn-primary">View All Invoices</a>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>