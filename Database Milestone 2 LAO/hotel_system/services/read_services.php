<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM services ORDER BY category, service_name";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Service Menu</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Service Menu</h1>
        <a href="create_service.php" class="btn btn-primary">+ Add New Service</a>
        <a href="../index.php" class="btn btn-secondary">← Back to Dashboard</a>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['service_id']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="update_service.php?id=<?php echo $row['service_id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete_service.php?id=<?php echo $row['service_id']; ?>" class="btn-delete" onclick="return confirm('Delete this service?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>