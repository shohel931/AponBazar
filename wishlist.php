<?php
include 'db.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add/Remove from wishlist via POST
if(isset($_POST['product_id'])){
    $product_id = intval($_POST['product_id']);

    // Check if already in wishlist
    $check = $conn->query("SELECT * FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");

    if($check->num_rows == 0){
        $conn->query("INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)");
    } else {
        $conn->query("DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");
    }
    header("Location: wishlist.php");
    exit;
}

// Remove from wishlist via GET
if(isset($_GET['remove'])){
    $wish_id = intval($_GET['remove']);
    $conn->query("DELETE FROM wishlist WHERE id=$wish_id AND user_id=$user_id");
    header("Location: wishlist.php");
    exit;
}

// Fetch wishlist items
$result = $conn->query("
    SELECT w.id as wish_id, p.* 
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id=$user_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Wishlist</title>
<link rel="stylesheet" href="css/wishlist.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<?php include 'includs/header.php'; ?>

<br><br><br>

<section class="wishlist_page">
    <h2>My Wishlist</h2>
    <div class="wishlist_container">
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product_card">
                    <div class="product_img">
                        <a href="product-view.php?id=<?= $row['id'] ?>"><img src="img/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>"></a>
                    </div>
                    <div class="pname">
                        <a href="product-view.php?id=<?= $row['id'] ?>"><h3><?= htmlspecialchars($row['name']) ?></h3></a>
                        <p>৳<?= number_format($row['price'], 2) ?></p>
                        <a href="wishlist.php?remove=<?= $row['wish_id'] ?>" class="remove_btn">Remove</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includs/footer.php'; ?>
<script src="js/header.js"></script>
<script src="js/cart.js"></script>
</body>
</html>
