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
    <title>Cart - AponBazar</title>
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
            <tbody id="cart-body">
                <tr>
                    <td class="product-info">
                        <a href="#"><img src="./img/apple.jpg" alt=""></a>
                        <span><a href="#">Fresh Apple</a></span>
                    </td>
                    <td>$10</td>
                    <td>
                        <input type="number" min="1" value="2" class="qty">
                    </td>
                    <td class="total-price">$20</td>
                    <td><button class="remove"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                <tr>
                    <td class="product-info">
                        <a href="#"><img src="./img/apple.jpg" alt=""></a>
                        <span><a href="#">Fresh Apple</a></span>
                    </td>
                    <td>$10</td>
                    <td>
                        <input type="number" min="1" value="2" class="qty">
                    </td>
                    <td class="total-price">$20</td>
                    <td><button class="remove"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                <tr>
                    <td class="product-info">
                        <a href="#"><img src="./img/sm.png" alt=""></a>
                        <span><a href="#">Fresh Apple</a></span>
                    </td>
                    <td>$10</td>
                    <td>
                        <input type="number" min="1" value="2" class="qty">
                    </td>
                    <td class="total-price">$20</td>
                    <td><button class="remove"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                <tr>
                    <td class="product-info">
                        <a href="#"><img src="./img/apple.jpg" alt=""></a>
                        <span><a href="#">Fresh Apple</a></span>
                    </td>
                    <td>$10</td>
                    <td>
                        <input type="number" min="1" value="2" class="qty">
                    </td>
                    <td class="total-price">$20</td>
                    <td><button class="remove"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
            </tbody>
        </table>

        <div class="cart-under">
            <a href="shop.php" class="continue-shopping"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>

            <div class="cart-summary">
            <h3>Cart Summary</h3>
            <p>Subtotal: <span id="subtotal">$28</span></p>
            <p>Shipping: <span>$5</span></p>
            <h4>Grand Total: <span id="grandtotal">$33</span></h4>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
        </div>
        
    </main>
    






<?php include 'includs/footer.php'; ?>
    <script src="js/header.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>