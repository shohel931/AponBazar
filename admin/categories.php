<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
$message = '';

// Handle Add Category
if(isset($_POST['add_category'])){
    $name = trim($_POST['name']);

    // Check if category already exists
    $check = $conn->prepare("SELECT id FROM categories WHERE name=?");
    $check->bind_param("s", $name);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $message = "❌ Category already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if($stmt->execute()){
            $message = "✅ Category successfully added!";
        } else {
            $message = "❌ Something went wrong!";
        }
    }
}

// Categories list
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Categories</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>

</style>
</head>
<body>
<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php" class="active">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Category Management</h1>

        <?php if($message): ?><p class="message"><?= $message ?></p><?php endif; ?>

        <!-- Add Category Form -->
        <form method="POST">
            <h2>Add New Category</h2>
            <input type="text" name="name" placeholder="Category Name" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>

        <!-- Categories List -->
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
            </tr>
            <?php while($cat = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= $cat['name'] ?></td>
                <td><?= $cat['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>
