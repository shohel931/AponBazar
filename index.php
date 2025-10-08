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
<br><br><br>
<!-- Hero Slider -->
<section id="slider" class="slider-container">
  <button class="slider-btn prev"><i class="fa-solid fa-chevron-left"></i></button>
  <div class="slider-main">
    <?php
    $sliders = $conn->query("SELECT * FROM sliders ORDER BY id DESC");
    $i = 0;
    while ($slide = $sliders->fetch_assoc()):
    ?>
      <div class="slide <?= $i === 0 ? 'active' : '' ?>">
        <a href="<?= $slide['link'] ?: '#' ?>">
          <img src="uploads/<?= htmlspecialchars($slide['image']) ?>" alt="Slider <?= $i+1 ?>">
        </a>
      </div>
    <?php 
      $i++;
      endwhile; 
    ?>
  </div>
  <div class="slider-dots">
    <?php for ($j=0; $j<$i; $j++): ?>
      <span class="dot <?= $j===0 ? 'active' : '' ?>"></span>
    <?php endfor; ?>
  </div>
  <button class="slider-btn next"><i class="fa-solid fa-chevron-right"></i></button>
</section>



<!-- Featured Categories -->
<section class="categories">
  <h2>Shop by Categories</h2>
  <div class="category-container">
    <?php
    $categories = $conn->query("SELECT * FROM categories ORDER BY id ASC");
    while ($cat = $categories->fetch_assoc()):
    ?>
      <div class="category-card">
        <a href="shop.php?category=<?= $cat['id'] ?>">
          <img src="uploads/<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
          <h3><?= htmlspecialchars($cat['name']) ?></h3>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Featured Products -->
<section class="featured-products">
  <h2>Featured Products</h2>
  <div class="product-container">
    <?php
    $products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
    while ($p = $products->fetch_assoc()):
    ?>
      <div class="product-card">
        <a href="product.php?id=<?= $p['id'] ?>">
          <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <p class="price">৳<?= number_format($p['price'],2) ?></p>
        </a>
        <button class="add-to-cart" data-id="<?= $p['id'] ?>"><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Promo Section -->
<section class="promo">
  <div class="promo-container">
    <h2>Special Offer</h2>
    <p>Get up to 50% off on selected items!</p>
    <a href="shop.php" class="btn">Shop Now</a>
  </div>
</section>

<!-- Footer -->
<?php include 'includs/footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
