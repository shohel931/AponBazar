<?php 
include 'db.php';

// // Redirect if not logged in
// if(!isset($_SESSION['user_id'])){
//     header("Location: login.php");
//     exit;
// }

$cart_items = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - AponBazar</title>
<link rel="stylesheet" href="css/cart.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
<?php include 'includs/header.php'; ?>

<br><br><br>

<main class="cart-page">
<table class="cart-table">
<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<?php
if($cart_items):
foreach($cart_items as $product_id => $qty):
    $product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
    $total_price = $product['price'] * $qty;
    $total += $total_price;
?>
<tr data-id="<?= $product_id ?>">
<td class="product-info">
<a href="product-view.php?id=<?= $product['id'] ?>"><img src="./img/<?= $product['image'] ?>" alt=""></a>
<span><a href="product-view.php?id=<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a></span>
</td>
<td>৳<?= number_format($product['price'],2) ?></td>
<td><input type="number" min="1" value="<?= $qty ?>" class="qty"></td>
<td class="total-price">৳<?= number_format($total_price,2) ?></td>
<td><button class="remove"><i class="fa-solid fa-trash"></i></button></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">Your cart is empty.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="cart-under">
<a href="shop.php" class="continue-shopping"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>

<div class="cart-summary">
<h3>Cart Summary</h3>
<p>Subtotal: <span id="subtotal">৳<?= number_format($total,2) ?></span></p>
<p>Shipping: <span>৳5</span></p>
<h4>Grand Total: <span id="grandtotal">৳<?= number_format($total+5,2) ?></span></h4>
<a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
</div>
</div>
</main>

<?php include 'includs/footer.php'; ?>
<script src="js/cart.js"></script>
<script src="js/header.js"></script>
</body>
</html>
