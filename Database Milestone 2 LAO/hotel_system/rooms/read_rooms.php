<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM rooms ORDER BY room_number";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room List</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Room List</h1>
        <a href="create_room.php" class="btn btn-primary">+ Add New Room</a>
        <a href="../index.php" class="btn btn-secondary">← Back to Dashboard</a>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price/Night</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['room_id']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['room_type']; ?></td>
                    <td>$<?php echo number_format($row['price_per_night'], 2); ?></td>
                    <td>
                        <span class="status <?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="update_room.php?id=<?php echo $row['room_id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete_room.php?id=<?php echo $row['room_id']; ?>" class="btn-delete" onclick="return confirm('Delete this room?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>