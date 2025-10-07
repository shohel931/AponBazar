<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
$message = '';

// Handle Add Product
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    //  Handle Image Upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../img/'.$image);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $desc, $price, $category_id, $image);
    if($stmt->execute()){
        $message = "✅ Product successfully added!";
    } else {
        $message = "❌ Something went wrong!";
    }
}

// Products list
$result = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Products</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
</head>
<body>
<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php" class="active">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Products Management</h1>

        <?php if($message): ?><p class="message"><?= $message ?></p><?php endif; ?>

        <!-- Add Product Form -->
        <form method="POST" enctype="multipart/form-data">
            <h2>Add New Product</h2>
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php while($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="file" name="image">
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <!-- Products List -->
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Image</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>৳<?= $row['price'] ?></td>
                <td><?= $row['category_name'] ?></td>
                <td><img src="../img/<?= $row['image'] ?>" alt=""></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>
