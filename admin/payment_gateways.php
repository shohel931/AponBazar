<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';

// Payment Order Data Load
$query = "SELECT id, name, email, phone, total, payment_method, transaction_id, payment_status 
          FROM orders ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Gateways - Admin</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 95%;
        margin: 20px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333 ;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background: #007bff;
        color: white;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    .status-pending {
        color: #ff9800;
        font-weight: bold;
    }
    .status-paid {
        color: #4CAF50;
        font-weight: bold;
    }
    .status-failed {
        color: #f44336;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="admin-container">

    <!-- ✅ Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="payment_gateways.php" class="active">Payment</a></li>
            <li><a href="payment_method.php">Payment Methods</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- ✅ Main Content -->
    <div class="main-content">
        <h1>Payment Gateway Orders</h1>

        <div class="container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= number_format($row['total'], 2) ?> ৳</td>
                    <td><?= htmlspecialchars($row['payment_method'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['transaction_id'] ?? 'N/A') ?></td>
                    <td class="status-<?= strtolower($row['payment_status']) ?>">
                        <?= htmlspecialchars($row['payment_status']) ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
