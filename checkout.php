<?php 
include 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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
      <form id="checkoutForm">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" id="name" required>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" id="email" required>
        </div>
        <div class="form-group">
          <label>Phone *</label>
          <input type="text" id="phone" required>
        </div>
        <div class="form-group">
          <label>Address *</label>
          <input type="text" id="address" required>
        </div>
        <div class="form-group">
          <label>City *</label>
          <input type="text" id="city" required>
        </div>
        <div class="form-group">
          <label>Payment Method *</label>
          <select id="payment" required>
            <option value="">-- Select Payment Method --</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
            <option value="cod">Cash on Delivery</option>
          </select>
        </div>
        <button type="submit" class="place-order">Place Order</button>
      </form>
    </section>

    <!-- Order Summary -->
    <aside class="order-summary">
      <h3>Your Order</h3>
      <div class="order-items">
        <div class="item">
          <span>Fresh Apple x2</span>
          <span>$20</span>
        </div>
        <div class="item">
          <span>Organic Tomato x1</span>
          <span>$8</span>
        </div>
      </div>
      <hr>
      <p>Subtotal: <span>$28</span></p>
      <p>Shipping: <span>$5</span></p>
      <h4>Total: <span>$33</span></h4>
    </aside>

  </div>
</main>

<?php include 'includs/footer.php'; ?>

    <script src="js/header.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>