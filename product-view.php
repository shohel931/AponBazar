<?php
include 'db.php';
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($product['name']) ?> - AponBazar</title>
<link rel="stylesheet" href="css/product-view.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<?php include 'includs/header.php'; ?>

<br><br><br><br><br>

<div class="product_view_container">
    <div class="product_image">
        <img src="./img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product_details">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="price">৳<?= number_format($product['price'],2) ?></p>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <div class="actions">
    <!-- Add to Cart -->
    <form method="POST" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <button type="submit" name="add_to_cart" class="add_to_cart">
            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
        </button>
    </form>

    <!-- Add to Wishlist -->
    <form method="POST" action="wishlist.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <button type="submit" name="add_to_wishlist" class="wishlist">
            <i class="fa-regular fa-heart"></i> Add to Wishlist
        </button>
    </form>
</div>

    </div>
</div>

<?php include 'includs/footer.php'; ?>
<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
