<?php
include 'db.php';
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<h2><?= htmlspecialchars($product['name']) ?></h2>
<img src="./img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
<p>Price: ৳<?= number_format($product['price'],2) ?></p>
<p><?= htmlspecialchars($product['description']) ?></p>
