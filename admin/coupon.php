<?php
include '../db.php'; // adjust path if needed
session_start();

// Redirect if not admin (assume is_admin session)
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Handle add coupon
if(isset($_POST['add_coupon'])){
    $code = $conn->real_escape_string($_POST['code']);
    $discount_percent = floatval($_POST['discount_percent']);
    $active = isset($_POST['active']) ? 1 : 0;

    $conn->query("INSERT INTO coupons (code, discount_percent, active) VALUES ('$code', $discount_percent, $active)");
}

// Handle delete coupon
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM coupons WHERE id=$id");
}

// Fetch all coupons
$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Coupons</title>
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<style>
body {font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:0;}
.container {max-width: 1000px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
h2 {text-align:center; margin-bottom:20px;}
form {display:flex; flex-wrap: wrap; gap:10px; margin-bottom:30px;}
form input, form button {padding:10px; font-size:16px;}
form input[type="text"], form input[type="number"] {flex:1;}
form button {background:#28a745; color:#fff; border:none; cursor:pointer; border-radius:4px;}
form button:hover {background:#218838;}
table {width:100%; border-collapse: collapse;}
table th, table td {padding:12px; text-align:center; border-bottom:1px solid #ddd;}
table th {background:#007bff; color:#fff;}
.delete-btn {color:#fff; background:#dc3545; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; text-decoration:none;}
.delete-btn:hover {background:#c82333;}
.active-status {color: green; font-weight:bold;}
.inactive-status {color: red; font-weight:bold;}
</style>
</head>
<body>




</body>
</html>
<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php" class="active">Coupon Code</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
   
    <div class="container">
<h2>Coupon Code Management</h2>

<!-- Add Coupon Form -->
<form method="POST">
    <input type="text" name="code" placeholder="Coupon Code" required>
    <input type="number" name="discount_percent" placeholder="Discount %" required min="1" max="100">
    <label><input type="checkbox" name="active" checked> Active</label>
    <button type="submit" name="add_coupon"><i class="fa-solid fa-plus"></i> Add Coupon</button>
</form>

<!-- Coupon Table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Discount (%)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if($coupons->num_rows > 0): ?>
            <?php while($row = $coupons->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['code']) ?></td>
                    <td><?= $row['discount_percent'] ?>%</td>
                    <td>
                        <?= $row['active'] ? '<span class="active-status">Active</span>' : '<span class="inactive-status">Inactive</span>' ?>
                    </td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure to delete this coupon?')"><i class="fa-solid fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No coupons found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

</div>
