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
        $_SESSION['discount'] = $discount;
    } else {
        $error_msg = "Invalid coupon code!";
    }
}

// Handle coupon removal
if (isset($_POST['remove_coupon'])) {
    unset($_SESSION['applied_coupon']);
    unset($_SESSION['discount']);
    $discount = 0;
}

// Calculate totals
$discount = $_SESSION['discount'] ?? 0;
$grand_total = $total - $discount + 150; // 150 = shipping
// Handle checkout
if (isset($_POST['checkout'])) {
    if (count($cart_items) > 0) {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        $city = $conn->real_escape_string($_POST['city']);

        $_SESSION['checkout_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'total' => $grand_total,
            'discount' => $discount,
            'coupon' => $_SESSION['applied_coupon'] ?? ''
        ];

        // ✅ শুধু redirect করবো
        header("Location: payment.php");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AponBazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        .checkout-page {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px;
            flex-wrap: wrap;
        }

        .checkout-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .billing, .order-summary {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .billing {
            flex: 1 1 350px;
        }

        .order-summary {
            flex: 1 1 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .place-order {
            background: #2e8b57;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .place-order:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .coupon-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .coupon-actions button {
            background: #2e8b57;
            color: #fff;
            border: none;
            padding: 7px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .coupon-actions .remove {
            background: #ff4d4d;
        }
    </style>
</head>
<body>

<?php include 'includs/header.php'; ?>
<br><br><br><br>

<main class="checkout-page">
    <div class="checkout-container">

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

                <div class="form-group coupon-actions">
                    <input type="text" name="coupon_code" placeholder="Enter Coupon Code" value="<?= $_SESSION['applied_coupon'] ?? '' ?>">
                    <?php if (isset($_SESSION['applied_coupon'])): ?>
                        <button type="submit" name="remove_coupon" class="remove">Remove</button>
                    <?php else: ?>
                        <button type="submit" name="apply_coupon">Apply</button>
                    <?php endif; ?>
                </div>

                <button type="submit" name="checkout" class="place-order" <?= count($cart_items) == 0 ? 'disabled' : '' ?>>
                    <?= count($cart_items) == 0 ? 'Cart is Empty' : 'Place Order' ?>
                </button>
            </form>
        </section>

        <aside class="order-summary">
            <h3>Your Order</h3>
            <div class="order-items">
                <?php if (count($cart_items) > 0): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="item">
                            <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
                            <span>৳<?= number_format($item['total_price'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
            <hr>
            <p>Subtotal: <span>৳<?= number_format($total, 2) ?></span></p>
            <p>Discount: <span>৳<?= number_format($discount, 2) ?></span></p>
            <p>Shipping: <span>৳150</span></p>
            <h4>Total: <span>৳<?= number_format($grand_total, 2) ?></span></h4>
        </aside>

    </div>
</main>

<?php include 'includs/footer.php'; ?>

<script src="js/header.js"></script>
</body>
</html>
