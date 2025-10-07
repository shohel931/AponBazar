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
            <a href="shop.html" class="continue-shopping"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>

            <div class="cart-summary">
            <h3>Cart Summary</h3>
            <p>Subtotal: <span id="subtotal">$28</span></p>
            <p>Shipping: <span>$5</span></p>
            <h4>Grand Total: <span id="grandtotal">$33</span></h4>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
        </div>
        
    </main>
    











<footer id="footer">
  <div class="footer-container">
    <!-- About -->
    <div class="footer-box">
      <img src="./img/logo.png" alt="">
      <p>Your trusted online grocery store. Fresh, fast, and affordable delivery right to your door.</p>
      <div class="social-links">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="footer-box">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="shop.html">Shop</a></li>
        <li><a href="#">Cart</a></li>
        <li><a href="#">Checkout</a></li>
        <li><a href="#">My Account</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>

    <!-- Customer Service -->
    <div class="footer-box">
      <h4>Customer Service</h4>
      <ul>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms & Conditions</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#">FAQs</a></li>
      </ul>
    </div>

    <!-- Newsletter -->
    <div class="footer-box">
      <h4>Newsletter</h4>
      <p>Subscribe to get latest offers and updates.</p>
      <form class="newsletter-form">
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 AponBazar. All Rights Reserved. | Designed by Shohel Rana</p>
  </div>
</footer>



 
    <footer class="footer">
        <div class="fcontainer">
            <div class="icon_box">
                <a title="Home" href="index.html"><i class="fa-solid fa-house"></i></a>
            </div>
            <div class="icon_box">
                <a title="Cart" href="cart.html"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
            <div class="icon_box">
                <a title="All Products" href="shop.html"><i class="fa-brands fa-product-hunt"></i></a>
            </div>
            <div class="icon_box">
                <a title="Favorite" href="#"><i class="fa-regular fa-heart"></i></a>
            </div>
            <div class="icon_box">
                <a title="Account" href="#"><i class="fa-solid fa-user"></i></a>
            </div>
        </div>
    </footer>






<?php include 'includs/footer.php'; ?>
    <script src="js/header.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>