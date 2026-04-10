<?php
include '../includes/db_connect.php';

$service_id = $_GET['id'];

// Fetch current data
$sql = "SELECT * FROM services WHERE service_id = $service_id";
$result = $conn->query($sql);
$service = $result->fetch_assoc();

if (!$service) {
    header("Location: read_services.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    $sql = "UPDATE services SET 
            service_name = '$service_name',
            category = '$category',
            price = '$price'
            WHERE service_id = $service_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: read_services.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Service</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Update Service</h1>
        <a href="read_services.php" class="btn btn-secondary">← Back to Services</a>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Service Name:</label>
                <input type="text" name="service_name" value="<?php echo $service['service_name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Category:</label>
                <select name="category" required>
                    <option value="Restaurant" <?php echo ($service['category'] == 'Restaurant') ? 'selected' : ''; ?>>Restaurant</option>
                    <option value="Spa" <?php echo ($service['category'] == 'Spa') ? 'selected' : ''; ?>>Spa</option>
                    <option value="MiniBar" <?php echo ($service['category'] == 'MiniBar') ? 'selected' : ''; ?>>Mini Bar</option>
                    <option value="Laundry" <?php echo ($service['category'] == 'Laundry') ? 'selected' : ''; ?>>Laundry</option>
                    <option value="Other" <?php echo ($service['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?php echo $service['price']; ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Service</button>
        </form>
    </div>
</body>
</html>