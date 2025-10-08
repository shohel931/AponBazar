<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ User Info
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// ✅ User Orders
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders = $order_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account - AponBazar</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/account.css">
</head>
<body>

<?php include 'includs/header.php'; ?>

<br><br><br><br><br>

<div class="account-container">
    <div class="profile-card">
        <img src="img/user.png" alt="User" class="profile-img">
        <h2><?= htmlspecialchars($user['name']) ?></h2>
        <p><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
        <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($user['number'] ?? 'N/A') ?></p>
        <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($user['address'] ?? 'No address added') ?></p>
        <a href="settings.php" class="btn">Edit Profile</a>
        <a href="logout.php" class="btn logout">Logout</a>
    </div>

    <div class="orders-card">
        <h2><i class="fa-solid fa-box"></i> My Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total (৳)</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if($orders->num_rows > 0): ?>
                    <?php while($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td><span class="status <?= strtolower($order['payment_status']) ?>"><?= $order['payment_status'] ?></span></td>
                        <td><span class="status <?= strtolower($order['order_status']) ?>"><?= $order['order_status'] ?></span></td>
                        <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includs/footer.php'; ?>

</body>
</html>
