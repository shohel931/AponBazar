<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
</head>
<body>
<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Catagorys</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="cards">
            <div class="card">
                <?php
                $p = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc();
                ?>
                <h3>Products</h3>
                <p><?= $p['total'] ?></p>
            </div>
            <div class="card">
                <?php
                $c = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc();
                ?>
                <h3>Catagory</h3>
                <p><?= $c['total'] ?></p>
            </div>
            <div class="card">
                <?php
                $o = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc();
                ?>
                <h3>Orders</h3>
                <p><?= $o['total'] ?></p>
            </div>
            <div class="card">
                <?php
                $u = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc();
                ?>
                <h3>Users</h3>
                <p><?= $u['total'] ?></p>
            </div>
        </div>
    </div>

</div>
</body>
</html>
