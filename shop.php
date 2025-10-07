<?php 
include 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch products from DB
$products = $conn->query("SELECT p.*, c.name as category_name 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id=c.id 
                          ORDER BY p.id DESC");

// Fetch wishlist items
$wishlist = [];
$wish_res = $conn->query("SELECT product_id FROM wishlist WHERE user_id=$user_id");
while($w = $wish_res->fetch_assoc()){
    $wishlist[] = $w['product_id'];
}

// Fetch cart items
$cart = [];
$cart_res = $conn->query("SELECT product_id FROM cart WHERE user_id=$user_id");
while($c = $cart_res->fetch_assoc()){
    $cart[] = $c['product_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/shop.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<title>Shop - AponBazar</title>
</head>
<body>
<?php include 'includs/header.php'; ?>

<br><br><br><br>

<section class="shop_page">
    <h2>All Products</h2>
    <div class="shop_container">
        <div class="filter">
            <!-- Filter section (optional) -->
            <h2>Filters</h2>
        </div>

        <div class="products">
            <?php while($row = $products->fetch_assoc()): ?>
            <div class="product_card">
                <div class="product_img">
                    <a href="product-view.php?id=<?= $row['id'] ?>">
                        <img src="./img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </a>
                    <form method="POST" action="wishlist.php">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" title="Add to Wishlist" class="wishlist">
                            <i class="<?= in_array($row['id'],$wishlist)?'fa-solid':'fa-regular' ?> fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="pname">
                    <a href="product-view.php?id=<?= $row['id'] ?>">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                    </a>
                    <div class="ratting">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fa-regular fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p>৳<?= number_format($row['price'],2) ?></p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <?= in_array($row['id'],$cart)?'Added':'Add to Cart' ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includs/footer.php'; ?>
<script src="js/header.js"></script>
<script src="js/script.js"></script>
</body>
</html>
