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
    $order_status = $_POST['order_status'];
    $payment_status = $_POST['payment_status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status=?, payment_status=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssi", $order_status, $payment_status, $order_id);
    $stmt->execute();
}

// ✅ Delete order
if(isset($_POST['delete_order'])){
    $order_id = $_POST['order_id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: orders.php"); // refresh after delete
    exit;
}

// ✅ Fetch orders with user info
$sql = "SELECT o.*, u.name AS user_name, u.email, u.number 
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
/* ✅ Extra styling for delete button */
.delete-btn {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;
}
.delete-btn:hover {
    background: #c0392b;
}
form.inline {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
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
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="slider.php">Add Slider</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Orders</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Total (৳)</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Address</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['number'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td><span class="status <?= strtolower($order['payment_status']) ?>"><?= $order['payment_status'] ?></span></td>
                        <td><span class="status <?= strtolower($order['order_status']) ?>"><?= $order['order_status'] ?></span></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <form method="POST" class="inline">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="payment_status">
                                    <option value="Pending" <?= $order['payment_status']=='Pending'?'selected':'' ?>>Pending</option>
                                    <option value="Paid" <?= $order['payment_status']=='Paid'?'selected':'' ?>>Paid</option>
                                    <option value="Failed" <?= $order['payment_status']=='Failed'?'selected':'' ?>>Failed</option>
                                </select>
                                <select name="order_status">
                                    <option value="Processing" <?= $order['order_status']=='Processing'?'selected':'' ?>>Processing</option>
                                    <option value="Shipped" <?= $order['order_status']=='Shipped'?'selected':'' ?>>Shipped</option>
                                    <option value="Delivered" <?= $order['order_status']=='Delivered'?'selected':'' ?>>Delivered</option>
                                    <option value="Cancelled" <?= $order['order_status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" name="delete_order" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
