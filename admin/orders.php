<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';

// ✅ Update order status
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

// ✅ Fetch orders
$sql = "SELECT o.*, u.name AS user_name, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.id DESC";
$orders = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Orders</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
/* Order Page Styling */
.main-content h1 { margin-bottom:20px; }
table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); border-radius:10px; overflow:hidden; }
table th, table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
table th { background:#f7f7f7; color:#333; font-weight:600; }
tr:hover { background:#f9f9f9; }
form select, form button {
    padding:5px 8px;
    border-radius:4px;
    border:1px solid #ccc;
    font-size:14px;
}
form button {
    background:#007bff;
    color:#fff;
    border:none;
    cursor:pointer;
}
form button:hover {
    background:#0069d9;
}
.status {
    font-weight:bold;
    text-transform:capitalize;
}
.status.pending { color:#ff9800; }
.status.completed { color:#28a745; }
.status.cancelled { color:#dc3545; }
</style>
</head>
<body>

<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php" class="active">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>All Orders</h1>

        <table>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($order = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['user_name']) ?></td>
                <td><?= htmlspecialchars($order['email']) ?></td>
                <td>৳<?= number_format($order['total_price'], 2) ?></td>
                <td><span class="status <?= strtolower($order['status']) ?>"><?= $order['status'] ?></span></td>
                <td><?= $order['created_at'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status">
                            <option value="Pending" <?= $order['status']=='Pending'?'selected':'' ?>>Pending</option>
                            <option value="Completed" <?= $order['status']=='Completed'?'selected':'' ?>>Completed</option>
                            <option value="Cancelled" <?= $order['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>
