<?php 
include 'db.php';
?>

<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

<header id="header">
    <div class="container">
        <div class="logo">
            <a href="index.php"><img src="img/logo.png" alt="AponBazar Logo"></a>
        </div>
        <div class="search">
            <form action="">
                <input type="search" name="search" placeholder="Search your products" required>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="header_icon">
            <a class="icon" href="wishlist.php"><i class="fa-regular fa-heart"></i></a>
            <a class="icon" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>

            <div class="account icon">
    <button class="account-btn"><i class="fa-solid fa-user"></i></button>
    <ul class="dropdown">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="account.php"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Account'); ?></a></li>
            <li><a href="settings.php">Settings</a></li>
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
    <div class="catagory_nav">
    <ul>
    <?php
    // Fetch categories from DB
    $categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    while ($cat = $categories->fetch_assoc()):
    ?>
        <li><a href="shop.php?category=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
    <?php endwhile; ?>
  </ul>
</div>
</header>

<!-- Search Dropdown -->
<div id="dropdownSearch">
    <form action="">
        <input type="search" placeholder="Search products..." required>
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>

<!-- Side Menu -->
<nav id="sideMenu">
  <button class="close-btn">&times;</button>
  <ul>
    <?php
    // Fetch categories from DB
    $categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    while ($cat = $categories->fetch_assoc()):
    ?>
        <li><a href="shop.php?category=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
    <?php endwhile; ?>
  </ul>
</nav>

<div id="overlay"></div>


<script src="../js/header.js"></script>