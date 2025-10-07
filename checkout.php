<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_res = $conn->query("SELECT c.*, p.name, p.price 
                          FROM cart c 
                          JOIN products p ON c.product_id=p.id
                          WHERE c.user_id=$user_id");

$cart_items = [];
$total = 0;

while ($row = $cart_res->fetch_assoc()) {
    $row['total_price'] = $row['price'] * $row['quantity'];
    $total += $row['total_price'];
    $cart_items[] = $row;
}

// Initialize discount
$discount = 0;

// Handle coupon submission
if (isset($_POST['apply_coupon'])) {
    $coupon_code = $conn->real_escape_string($_POST['coupon_code']);
    $coupon_res = $conn->query("SELECT * FROM coupons WHERE code='$coupon_code' AND active=1 LIMIT 1");
    if ($coupon_res->num_rows > 0) {
        $coupon = $coupon_res->fetch_assoc();
        $discount = ($total * $coupon['discount_percent']) / 100;
        $_SESSION['applied_coupon'] = $coupon_code;
    } else {
        $discount = 0;
        $_SESSION['applied_coupon'] = null;
        $error_msg = "Invalid coupon code!";
    }
}

// Handle checkout form submit
if (isset($_POST['checkout'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $payment = $conn->real_escape_string($_POST['payment']);

    $grand_total = $total - $discount + 150; // 150 = shipping

    // Insert into orders table
    $conn->query("INSERT INTO orders 
        (user_id, name, email, phone, address, city, payment_method, total, coupon_code, discount)
        VALUES 
        ($user_id, '$name', '$email', '$phone', '$address', '$city', '$payment', $grand_total, '".($_SESSION['applied_coupon'] ?? '')."', $discount)");

    $order_id = $conn->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES ({$order_id}, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
    }

    // Clear user's cart
    $conn->query("DELETE FROM cart WHERE user_id=$user_id");

    // Clear coupon
    unset($_SESSION['applied_coupon']);

    // === Paymently Integration Start ===
    if ($payment === 'paymently') {

        $apiKey = 'YOUR_SECRET_API_KEY'; // এখানে তোমার Paymently API key বসাও
        $endpoint = 'https://shohelrana.paymently.io/api/checkout-v2';

        $payload = [
            'amount' => $grand_total,
            'currency' => 'BDT',
            'order_id' => $order_id,
            'customer' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ],
            'redirect_url' => 'https://yourdomain.com/payment-success.php?order_id=' . $order_id,
            'cancel_url' => 'https://yourdomain.com/payment-cancel.php?order_id=' . $order_id
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['payment_url'])) {
            header("Location: " . $result['payment_url']);
            exit;
        } else {
            echo "<p style='color:red;'>Payment initialization failed! Please try again.</p>";
        }
    } else {
        // For COD, bKash, Nagad etc.
        header("Location: payment.php?order_id=$order_id");
        exit;
    }
    // === Paymently Integration End ===
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Checkout - AponBazar</title>
</head>
<body>

<?php include 'includs/header.php'; ?>
<br><br><br>

<main class="checkout-page">
  <div class="checkout-container">

    <!-- Billing Form -->
    <section class="billing">
      <h3>Billing Details</h3>
      <?php if (isset($error_msg)) echo '<p style="color:red;">' . $error_msg . '</p>'; ?>
      <form method="POST">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" required>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label>Phone *</label>
          <input type="text" name="phone" required>
        </div>
        <div class="form-group">
          <label>Address *</label>
          <input type="text" name="address" required>
        </div>
        <div class="form-group">
          <label>City *</label>
          <input type="text" name="city" required>
        </div>
        <div class="form-group">
          <label>Payment Method *</label>
          <select name="payment" required>
            <option value="">-- Select Payment Method --</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
            <option value="paymently">Paymently</option>
            <option value="cod">Cash on Delivery</option>
          </select>
        </div>
        <div class="form-group">
          <label>Coupon Code</label>
          <input type="text" name="coupon_code" value="<?= $_SESSION['applied_coupon'] ?? '' ?>">
          <button class="coupon_code" type="submit" name="apply_coupon">Apply Coupon</button>
        </div>
        <button type="submit" name="checkout" class="place-order">Place Order</button>
      </form>
    </section>

    <aside class="order-summary">
      <h3>Your Order</h3>
      <div class="order-items">
        <?php foreach ($cart_items as $item): ?>
          <div class="item">
            <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
            <span>৳<?= number_format($item['total_price'], 2) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <hr>
      <p>Subtotal: <span>৳<?= number_format($total, 2) ?></span></p>
      <p>Discount: <span>৳<?= number_format($discount, 2) ?></span></p>
      <p>Shipping: <span>৳150</span></p>
      <h4>Total: <span>৳<?= number_format($total - $discount + 150, 2) ?></span></h4>
    </aside>

  </div>
</main>

<?php include 'includs/footer.php'; ?>

<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
