<?php 
include 'db.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add product to cart
if(isset($_POST['add_to_cart'])){
    $product_id = intval($_POST['product_id']);
    
    // Check if product already in cart
    $check = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");
    if($check->num_rows == 0){
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
}

// Remove from cart
if(isset($_POST['remove_id'])){
    $remove_id = intval($_POST['remove_id']);
    $conn->query("DELETE FROM cart WHERE user_id=$user_id AND product_id=$remove_id");
}

// Update quantity
if(isset($_POST['update_qty'])){
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    if($quantity < 1) $quantity = 1;
    $conn->query("UPDATE cart SET quantity=$quantity WHERE user_id=$user_id AND product_id=$product_id");
}


// Fetch cart items
$cart_res = $conn->query("SELECT c.*, p.name, p.price, p.image 
                          FROM cart c 
                          JOIN products p ON c.product_id=p.id
                          WHERE c.user_id=$user_id");

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
<?php if($cart_res->num_rows > 0): ?>
    <?php while($row = $cart_res->fetch_assoc()): 
        $total_price = $row['price'] * $row['quantity'];
        $total += $total_price;
    ?>
    <tr data-id="<?= $row['product_id'] ?>">
        <td class="product-info">
            <a href="product-view.php?id=<?= $row['product_id'] ?>"><img src="./img/<?= $row['image'] ?>" alt=""></a>
            <span><a href="product-view.php?id=<?= $row['product_id'] ?>"><?= htmlspecialchars($row['name']) ?></a></span>
        </td>
        <td>৳<?= number_format($row['price'],2) ?></td>
        <td>
    <form method="POST" style="display:inline;">
    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
    <input class="quantity" type="number" min="1" name="quantity" value="<?= $row['quantity'] ?>" onchange="this.form.submit()">
    <input type="hidden" name="update_qty" value="1">
</form>

</td>

        <td class="total-price">৳<?= number_format($total_price,2) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="remove_id" value="<?= $row['product_id'] ?>">
                <button type="submit" class="remove"><i class="fa-solid fa-trash"></i></button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="5">Your cart is empty.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="cart-under">
<a href="shop.php" class="continue-shopping"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>

<div class="cart-summary">
    <h3>Cart Summary</h3>
    <p>Subtotal: <span id="subtotal">৳<?= number_format($total,2) ?></span></p>
    <p>Shipping: <span>৳150</span></p>
    <h4>Total: <span id="grandtotal">৳<?= number_format($total+150,2) ?></span></h4>

    <?php if($cart_res->num_rows > 0): ?>
        <!-- Cart has products -->
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php else: ?>
        <!-- Cart empty, disable button -->
        <button class="checkout-btn" disabled style="cursor:not-allowed; opacity:0.6;">
            Proceed to Checkout
        </button>
    <?php endif; ?>
</div>

</div>
</main>

<?php include 'includs/footer.php'; ?>
<script src="js/cart.js"></script>
<script src="js/header.js"></script>
</body>
</html>
