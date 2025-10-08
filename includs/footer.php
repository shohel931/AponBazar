<?php 
include 'db.php';

// // Redirect to login if not logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }



?>
<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">



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
        <li><a href="shop.php">Shop</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="checkout.php">Checkout</a></li>
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
        <li><a href="admin/dashboard.php">Admin</a></li>
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
                <a title="Home" href="index.php"><i class="fa-solid fa-house"></i></a>
            </div>
            <div class="icon_box">
                <a title="Cart" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
            <div class="icon_box">
                <a title="All Products" href="shop.php"><i class="fa-brands fa-product-hunt"></i></a>
            </div>
            <div class="icon_box">
                <a title="Favorite" href="wishlist.php"><i class="fa-regular fa-heart"></i></a>
            </div>
            <div class="icon_box">
                <a title="Account" href="account.php"><i class="fa-solid fa-user"></i></a>
            </div>
        </div>
    </footer>

<script src="../js/header.js"></script>