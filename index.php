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
    <h1>Everyday Fresh & Organic Groceries</h1>
    <p>Healthy food for your family, delivered straight from the farm to your door.</p>
    <a href="shop.php">Shop Now</a>
  </div>
</section>

<section class="categories">
  <h2>Shop by Category</h2>
  <div class="category-container">
    <div class="category-card">
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
    <!-- <div class="category-card">Vegetables</div>
    <div class="category-card">Dairy</div>
    <div class="category-card">Meat</div>
    <div class="category-card">Snacks</div> -->
  </div>
</section>

<section class="featured">
  <h2>Featured Products</h2>
  <div class="product-container" id="productGrid"></div>
</section>


<script>
const products = [
  {id:1,name:'Fresh Apple',price:150,img:'img/apple.jpg'},
  {id:2,name:'Organic Tomato',price:80,img:'img/tomato.jpg'},
  {id:3,name:'Pure Milk',price:90,img:'img/milk.jpg'},
  {id:4,name:'Fresh Orange',price:160,img:'img/orange.jpg'},
  {id:5,name:'Basmati Rice',price:650,img:'img/rice.jpg'},
  {id:6,name:'Red Chili',price:40,img:'img/chili.jpg'}
];

const grid=document.getElementById('productGrid');
function renderProducts(){
  grid.innerHTML='';
  products.forEach(p=>{
    grid.innerHTML+=`<div class="product-card">
      <img src="${p.img}" alt="${p.name}">
      <h4>${p.name}</h4>
      <p>৳${p.price}</p>
      <a href="#" class="btn" onclick="addToCart(${p.id})">Add to Cart</a>
    </div>`;
  });
}
renderProducts();

function addToCart(id){
  alert('Added to cart: '+products.find(p=>p.id===id).name);
}
</script>
<!-- Footer -->
<?php include 'includs/footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
