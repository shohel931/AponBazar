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
<title>Payment Gateways - Admin</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
.main-content {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

/* Payment settings box */
.settings-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}
.settings-container h2 {
    text-align: center;
    margin-bottom: 20px;
}
.message {
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
    color: green;
}
.tabs {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    gap: 5px;
}
.tab-btn {
    flex: 1;
    padding: 12px;
    cursor: pointer;
    background: #f4f4f4;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}
.tab-btn.active {
    background: #007bff;
    color: #fff;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.tab-content form {
    display: flex;
    flex-direction: column;
}
.tab-content form label {
    margin-top: 10px;
    font-weight: 500;
}
.tab-content form input[type="text"],
.tab-content form select {
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}
.tab-content form button {
    margin-top: 20px;
    padding: 12px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}
.tab-content form button:hover {
    opacity: 0.85;
}
</style>
</head>
<body>

<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="Logo">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="payment_gateways.php">Payment</a></li>
            <li><a href="payment_method.php" class="active">Payment Methods</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Payment Methods</h1>

        <div class="settings-container">
            <h2>Manage Payment Accounts</h2>
            <?php if(isset($message)) echo "<p class='message'>$message</p>"; ?>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" onclick="openTab(event, 'bkash')">Bkash</button>
                <button class="tab-btn" onclick="openTab(event, 'nagad')">Nagad</button>
                <button class="tab-btn" onclick="openTab(event, 'rocket')">Rocket</button>
                <button class="tab-btn" onclick="openTab(event, 'upay')">Upay</button>
            </div>

            <!-- Bkash -->
            <div id="bkash" class="tab-content active">
                <form method="POST">
                    <label>Bkash Number</label>
                    <input type="text" name="bkash_number" placeholder="Enter Bkash Number" required>

                    <label>Account Type</label>
                    <select name="bkash_type" required>
                        <option value="Personal">Personal</option>
                        <option value="Agent">Agent</option>
                    </select>

                    <button type="submit" name="save_bkash">Save</button>
                </form>
            </div>

            <!-- Nagad -->
            <div id="nagad" class="tab-content">
                <form method="POST">
                    <label>Nagad Number</label>
                    <input type="text" name="nagad_number" placeholder="Enter Nagad Number" required>

                    <label>Account Type</label>
                    <select name="nagad_type" required>
                        <option value="Personal">Personal</option>
                        <option value="Agent">Agent</option>
                    </select>

                    <button type="submit" name="save_nagad">Save</button>
                </form>
            </div>

            <!-- Rocket -->
            <div id="rocket" class="tab-content">
                <form method="POST">
                    <label>Rocket Number</label>
                    <input type="text" name="rocket_number" placeholder="Enter Rocket Number" required>

                    <label>Account Type</label>
                    <select name="rocket_type" required>
                        <option value="Personal">Personal</option>
                        <option value="Agent">Agent</option>
                    </select>

                    <button type="submit" name="save_rocket">Save</button>
                </form>
            </div>

            <!-- Upay -->
            <div id="upay" class="tab-content">
                <form method="POST">
                    <label>Upay Number</label>
                    <input type="text" name="upay_number" placeholder="Enter Upay Number" required>

                    <label>Account Type</label>
                    <select name="upay_type" required>
                        <option value="Personal">Personal</option>
                        <option value="Agent">Agent</option>
                    </select>

                    <button type="submit" name="save_upay">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openTab(evt, tabName) {
    const tabContents = document.querySelectorAll('.tab-content');
    const tabBtns = document.querySelectorAll('.tab-btn');

    tabContents.forEach(tab => tab.classList.remove('active'));
    tabBtns.forEach(btn => btn.classList.remove('active'));

    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>

</body>
</html>
