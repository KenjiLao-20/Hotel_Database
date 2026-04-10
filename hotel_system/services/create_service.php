<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    $sql = "INSERT INTO services (service_name, category, price) 
            VALUES ('$service_name', '$category', '$price')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Service added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Service</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Service</h1>
        <a href="read_services.php" class="btn btn-secondary">← Back to Services</a>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Service Name:</label>
                <input type="text" name="service_name" required>
            </div>
            
            <div class="form-group">
                <label>Category:</label>
                <select name="category" required>
                    <option value="Restaurant">Restaurant</option>
                    <option value="Spa">Spa</option>
                    <option value="MiniBar">Mini Bar</option>
                    <option value="Laundry">Laundry</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Service</button>
        </form>
    </div>
</body>
</html>