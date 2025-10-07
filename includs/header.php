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
   
    <header id="header">
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="img/logo.png" alt=""></a>
            </div>
            <div class="search">
                <form action="">
                    <input type="search" name="search" id="" placeholder="Search your products" required>
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="header_icon">
                <a class="icon" href="#"><i class="fa-regular fa-heart"></i></a>
                <a class="icon" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>

                <div class="acoount icon">
                    <button><i class="fa-solid fa-user"></i></button>
                    <ul class="dopdown">
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="account.php"><?= htmlspecialchars($_SESSION['user_name']); ?></a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <button class="search-toggle" style="display: none;"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button class="mobile-menu" style="display: none;"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
    </header>
    <!-- Search -->
<div id="dropdownSearch">
    <form action="">
        <input type="search" placeholder="Search products..." required>
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>
<br>
<!-- Side Menu -->
<nav id="sideMenu">
  <button class="close-btn">&times;</button>
  <ul>
    <li><a href="#">Category 1</a></li>
    <li><a href="#">Category 2</a></li>
    <li><a href="#">Category 3</a></li>
    <li><a href="#">Category 4</a></li>
    <li><a href="#">Category 5</a></li>
    <li><a href="#">Category 6</a></li>
    <li><a href="#">Category 7</a></li>
    <li><a href="#">Category 8</a></li>
    <li><a href="#">Category 9</a></li>
    <li><a href="#">Category 10</a></li>
  </ul>
</nav>

<div id="overlay"></div>

<script src="../js/header.js"></script>