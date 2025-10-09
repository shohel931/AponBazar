<?php
include 'db.php';
session_start();

if (!isset($_GET['order_id'])) {
    header("Location: checkout.php");
    exit;
}

$order_id = intval($_GET['order_id']);
$order = $conn->query("SELECT * FROM orders WHERE id=$order_id")->fetch_assoc();

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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .payment-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2e8b57;
        }

        .order-summary {
            background: #f9f9f9;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
        }

        .order-summary p {
            margin: 5px 0;
            font-size: 15px;
        }

        .payment-options {
            margin-top: 20px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 10px 15px;
            margin-bottom: 15px;
            transition: 0.3s;
            cursor: pointer;
        }

        .payment-option:hover {
            border-color: #2e8b57;
            background: #f0fff0;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .payment-option img {
            width: 40px;
            margin-right: 10px;
        }

        .payment-btn {
            display: block;
            width: 100%;
            background: #2e8b57;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .payment-btn:hover {
            background: #256d45;
        }

        @media (max-width: 600px) {
            .payment-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Complete Your Payment</h2>

    <div class="order-summary">
        <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Total Amount:</strong> ৳<?= number_format($order['total'], 2) ?></p>
    </div>

    <form action="payment_process.php" method="POST">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

        <div class="payment-options">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="bkash" required>
                <img src="./img/bkash.png" alt="BKash">
                <span>BKash Payment</span>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_method" value="nagad">
                <img src="./img/nagad.png" alt="Nagad">
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
                <img src="./img/cash.png" alt="Cash on Delivery">
                <span>Cash on Delivery (COD)</span>
            </label>
        </div>

        <button type="submit" class="payment-btn">Proceed to Payment</button>
    </form>
</div>

</body>
</html>
