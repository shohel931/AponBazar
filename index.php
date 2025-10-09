<?php 
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AponBazar - Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/slider.css">
</head>
<body>

<!-- Header -->
<?php include 'includs/header.php'; ?>
  <!-- HERO SECTION -->
  <section class="hero">
    <div class="hero-content">
      <h1>Fresh & Organic Groceries</h1>
      <p>Delivered to your doorstep with care and freshness.</p>
      <a href="shop.php" class="btn">Shop Now</a>
    </div>
  </section>

  <!-- CATEGORY SECTION -->
  <section class="categories">
    <h2>Shop by Category</h2>
    <div class="category-container">
      <div class="category-card">
        <img src="img/fruits.jpg" alt="Fruits">
        <h4>Fruits</h4>
      </div>
      <div class="category-card">
        <img src="img/vegetables.jpg" alt="Vegetables">
        <h4>Vegetables</h4>
      </div>
      <div class="category-card">
        <img src="img/dairy.jpg" alt="Dairy">
        <h4>Dairy</h4>
      </div>
      <div class="category-card">
        <img src="img/meat.jpg" alt="Meat">
        <h4>Meat</h4>
      </div>
    </div>
  </section>

  <!-- FEATURED PRODUCTS -->
  <section class="featured">
    <h2>Featured Products</h2>
    <div class="product-container">
      <div class="product-card">
        <img src="img/apple.jpg" alt="Apple">
        <h4>Fresh Apple</h4>
        <p>৳150/kg</p>
        <a href="#" class="btn">Add to Cart</a>
      </div>
      <div class="product-card">
        <img src="img/tomato.jpg" alt="Tomato">
        <h4>Organic Tomato</h4>
        <p>৳80/kg</p>
        <a href="#" class="btn">Add to Cart</a>
      </div>
      <div class="product-card">
        <img src="img/milk.jpg" alt="Milk">
        <h4>Fresh Milk</h4>
        <p>৳90/L</p>
        <a href="#" class="btn">Add to Cart</a>
      </div>
    </div>
  </section>

<!-- Footer -->
<?php include 'includs/footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
