<?php
include 'db.php';
session_start();

if (!isset($_GET['order_id'])) {
    die("Invalid Request");
}

$order_id = intval($_GET['order_id']);

// Update order status as cancelled
$conn->query("UPDATE orders SET payment_status='cancelled' WHERE id=$order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        body { text-align: center; padding: 60px; font-family: Arial; background: #f5f5f5; }
        .cancel-box { background: #fff; border-radius: 10px; display: inline-block; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .cancel-box h2 { color: orange; }
        .cancel-box a { text-decoration: none; background: orange; color: #fff; padding: 10px 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="cancel-box">
        <h2>⚠️ Payment Cancelled</h2>
        <p>You have cancelled your payment for Order #<?= $order_id ?>.</p>
        <a href="checkout.php">Return to Checkout</a>
    </div>
</body>
</html>
