<?php
session_start();
include 'db.php';

// get order_id from query string
if (!isset($_GET['order_id'])) {
    header("Location: checkout.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// fetch order safely
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Invalid order!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - AponBazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .payment-container { max-width: 600px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2e8b57; }
        .order-summary { background: #f9f9f9; padding: 15px; margin-top: 15px; border-radius: 8px; }
        .order-summary p { margin: 5px 0; font-size: 15px; }
        .payment-options { margin-top: 20px; }
        .payment-option { display: flex; height: 30px; align-items: center; border: 2px solid #ddd; border-radius: 10px; padding: 10px 15px; margin-bottom: 15px; transition: 0.3s; cursor: pointer; }
        .payment-option:hover { border-color: #2e8b57; background: #f0fff0; }
        .payment-option input { margin-right: 10px; }
        .payment-option img { width: 48px; margin-right: 10px; }
        .payment-btn { display: block; width: 100%; background: #2e8b57; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 16px; margin-top: 15px; cursor: pointer; transition: 0.3s; }
        .payment-btn:hover { background: #256d45; }
        @media (max-width: 600px) { .payment-container { margin: 20px; padding: 20px; } }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Complete Your Payment</h2>

    <div class="order-summary">
        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Total Amount:</strong> ৳<?= number_format($order['total'], 2) ?></p>
    </div>

    <form id="paymentForm">
        <input type="hidden" id="order_id" value="<?= htmlspecialchars($order['id']) ?>">

        <div class="payment-options">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="bkash" required>
                <img src="./img/bkash.png" alt="BKash">
                <span>BKash Payment</span>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="nagad">
                <img src="./img/nagad.jpeg" alt="Nagad">
                <span>Nagad Payment</span>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="rocket">
                <img src="./img/rocket.png" alt="Rocket">
                <span>Rocket Payment</span>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="upay">
                <img src="./img/upay.png" alt="Upay">
                <span>Upay Payment</span>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="cod">
                <img src="./img/cash.jpeg" alt="Cash on Delivery">
                <span>Cash on Delivery</span>
            </label>
        </div>

        <button type="button" class="payment-btn" onclick="redirectPayment()">Proceed to Payment</button>
    </form>
</div>

<script>
function redirectPayment() {
    const method = document.querySelector('input[name="payment_method"]:checked');
    const orderId = document.getElementById('order_id').value;

    if (!method) {
        alert('Please select a payment method!');
        return;
    }

    let url = '';

    switch (method.value) {
        case 'bkash':
            url = 'bkash.php?order_id=' + orderId;
            break;
        case 'nagad':
            url = 'nagad.php?order_id=' + orderId;
            break;
        case 'rocket':
            url = 'rocket.php?order_id=' + orderId;
            break;
        case 'upay':
            url = 'upay.php?order_id=' + orderId;
            break;
        case 'cod':
            url = 'cod.php?order_id=' + orderId;
            break;
        default:
            alert('Invalid payment method!');
            return;
    }

    window.location.href = url;
}
</script>

</body>
</html>
