<?php
include 'db.php';
session_start();

if (!isset($_GET['order_id'])) {
    die("Invalid Request");
}

$order_id = intval($_GET['order_id']);

// Paymently credentials
$apiKey = 'O6b7HOJx6hvIAqjbQNvspgp5cMs8nyQDG93VSEts'; 
$verifyUrl = 'https://shohelrana.paymently.io/api/verify-payment?order_id=' . $order_id;

// Verify payment status from Paymently API
$ch = curl_init($verifyUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// Check payment response
if (isset($result['status']) && $result['status'] === 'success') {

    // Update order payment status
    $conn->query("UPDATE orders SET payment_status='paid' WHERE id=$order_id");

    // Show success message
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Successful</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/cart.css">
        <style>
            body { text-align: center; padding: 60px; font-family: Arial; background: #f5f5f5; }
            .success-box { background: #fff; border-radius: 10px; display: inline-block; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .success-box h2 { color: green; }
            .success-box a { text-decoration: none; background: green; color: #fff; padding: 10px 20px; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="success-box">
            <h2>✅ Payment Successful!</h2>
            <p>Your order <strong>#<?= $order_id ?></strong> has been paid successfully.</p>
            <a href="orders.php">View My Orders</a>
        </div>
    </body>
    </html>
    <?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Failed</title>
        <style>
            body { text-align: center; padding: 60px; font-family: Arial; background: #f5f5f5; }
            .error-box { background: #fff; border-radius: 10px; display: inline-block; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .error-box h2 { color: red; }
            .error-box a { text-decoration: none; background: red; color: #fff; padding: 10px 20px; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>❌ Payment Failed!</h2>
            <p>We could not verify your payment for Order #<?= $order_id ?>.</p>
            <a href="checkout.php">Try Again</a>
        </div>
    </body>
    </html>
    <?php
}
?>
