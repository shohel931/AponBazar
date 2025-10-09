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
    header("Location: orders.php");
    exit;
}

// ✅ Fetch orders
$sql = "SELECT o.*, u.name AS user_name, u.number 
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
body {
    font-family: 'Poppins', sans-serif;
    background: #eef2f8;
}
.main-content {
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    margin: 25px;
    border: 1px solid #e4e9f1;
}
h1 {
    font-size: 26px;
    color: #1e293b;
    margin-bottom: 25px;
    font-weight: 600;
}

/* ✅ Table Design */
.table-container {
    overflow-x: auto;
    border-radius: 10px;
}
table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
}
thead {
    background: #2563eb;
    color: #fff;
}
th, td {
    padding: 14px 12px;
    text-align: center;
    font-size: 15px;
}
tbody tr {
    background: #fff;
    border-bottom: 1px solid #e9edf4;
    transition: all 0.2s ease-in-out;
}
tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}
th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

/* ✅ Status Badges */
.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    min-width: 85px;
}
.status.pending { background: #fbbf24; color: #fff; }
.status.paid { background: #22c55e; color: #fff; }
.status.failed { background: #ef4444; color: #fff; }
.status.processing { background: #3b82f6; color: #fff; }
.status.shipped { background: #8b5cf6; color: #fff; }
.status.delivered { background: #16a34a; color: #fff; }
.status.received { background: #10b981; color: #fff; }
.status.cancelled { background: #dc2626; color: #fff; }

/* ✅ Buttons & Inputs */
button, select {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}
select:hover {
    border-color: #3b82f6;
}
button {
    border: none;
    color: white;
}
.view-btn {
    background: #2563eb;
}
.view-btn:hover {
    background: #1d4ed8;
}
.delete-btn {
    background: #ef4444;
}
.delete-btn:hover {
    background: #dc2626;
}
button[name="update_status"] {
    background: #22c55e;
}
button[name="update_status"]:hover {
    background: #16a34a;
}
form.inline {
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: center;
}

/* ✅ Modal Popup (modern glass look) */
.modal {
  display: none;
  position: fixed;
  z-index: 999;
  inset: 0;
  background-color: rgba(0,0,0,0.55);
  backdrop-filter: blur(3px);
}
.modal-content {
  background: #fff;
  margin: 6% auto;
  padding: 25px 30px;
  border-radius: 14px;
  width: 60%;
  max-width: 720px;
  position: relative;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-20px);}
  to {opacity: 1; transform: translateY(0);}
}
.close {
  color: #475569;
  position: absolute;
  right: 18px;
  top: 10px;
  font-size: 26px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}
.close:hover {
  color: #ef4444;
}
.product-item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding: 10px 0;
}
.product-item img {
  width: 60px;
  height: 60px;
  margin-right: 10px;
  border-radius: 8px;
  object-fit: cover;
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
            <li><a href="payment_gateways.php">Payment</a></li>
            <li><a href="payment_method.php">Payment Methods</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>📦 Manage Orders</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total (৳)</th>
                        <th>Method</th>
                        <th>Txn ID</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['number'] ?? 'N/A') ?></td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($order['transaction_id'] ?? 'N/A') ?></td>
                        <td><span class="status <?= strtolower($order['payment_status']) ?>"><?= $order['payment_status'] ?></span></td>
                        <td><span class="status <?= strtolower($order['order_status']) ?>"><?= $order['order_status'] ?></span></td>
                        <td>
                            <button class="view-btn" onclick="viewOrder(<?= $order['id'] ?>)">👁 View</button>

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
                                    <option value="Received" <?= $order['order_status']=='Received'?'selected':'' ?>>Received</option>
                                    <option value="Cancelled" <?= $order['order_status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>

                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" name="delete_order" class="delete-btn">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Modal -->
<div id="orderModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('orderModal').style.display='none'">&times;</span>
    <h2>🛒 Order Details</h2>
    <div id="orderDetails"></div>
  </div>
</div>

<script>
function viewOrder(orderId) {
    fetch('view_order.php?id=' + orderId)
    .then(res => res.text())
    .then(data => {
        document.getElementById('orderDetails').innerHTML = data;
        document.getElementById('orderModal').style.display = 'block';
    });
}
</script>
</body>
</html>
