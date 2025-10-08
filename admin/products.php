<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
$message = '';

// ==================== ADD PRODUCT ====================
if(isset($_POST['add_product'])){
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Prevent duplicate insert on refresh
    if (!isset($_SESSION['last_added']) || $_SESSION['last_added'] !== $name) {

        // Image Upload
        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
            $image = time().'_'.$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../img/'.$image);
        }

        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $desc, $price, $category_id, $image);
        if($stmt->execute()){
            $_SESSION['last_added'] = $name;
            $message = "✅ Product successfully added!";
        } else {
            $message = "❌ Something went wrong!";
        }
    } else {
        $message = "⚠️ Product already added! Please refresh after clearing form.";
    }
}

// ==================== DELETE PRODUCT ====================
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // delete image file if exists
    $getImg = $conn->prepare("SELECT image FROM products WHERE id=?");
    $getImg->bind_param("i", $id);
    $getImg->execute();
    $imgResult = $getImg->get_result()->fetch_assoc();
    if($imgResult && file_exists("../img/".$imgResult['image'])){
        unlink("../img/".$imgResult['image']);
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $message = "🗑️ Product deleted successfully!";
    } else {
        $message = "❌ Failed to delete!";
    }
}

// ==================== EDIT PRODUCT ====================
if(isset($_POST['update_product'])){
    $id = $_POST['product_id'];
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Old image
    $stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $old = $stmt->get_result()->fetch_assoc();
    $image = $old['image'];

    // If new image uploaded
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        if(file_exists("../img/".$image)){
            unlink("../img/".$image);
        }
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../img/'.$image);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category_id=?, image=? WHERE id=?");
    $stmt->bind_param("ssdisi", $name, $desc, $price, $category_id, $image, $id);
    if($stmt->execute()){
        $message = "✅ Product updated successfully!";
    } else {
        $message = "❌ Failed to update!";
    }
}

// ==================== FETCH ====================
$result = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
$categories = $conn->query("SELECT * FROM categories");

// Get edit product if editing
$editProduct = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $editProduct = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Products</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
    table img { width: 50px; height: 50px; object-fit: cover; }
    form { background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    button { cursor: pointer; }
    .message { background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
    .edit, .delete { padding: 5px 8px; border-radius: 4px; text-decoration: none; }
    .edit { background: #007bff; color: #fff; }
    .delete { background: #dc3545; color: #fff; }
</style>
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
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="slider.php">Add Slider</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Products Management</h1>

        <?php if($message): ?><p class="message"><?= $message ?></p><?php endif; ?>

        <!-- Add or Edit Product Form -->
        <form method="POST" enctype="multipart/form-data">
            <h2><?= $editProduct ? 'Edit Product' : 'Add New Product' ?></h2>
            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?? '' ?>">
            <input type="text" name="name" placeholder="Product Name" value="<?= $editProduct['name'] ?? '' ?>" required>
            <textarea name="description" placeholder="Description"><?= $editProduct['description'] ?? '' ?></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" value="<?= $editProduct['price'] ?? '' ?>" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php while($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($editProduct && $editProduct['category_id']==$cat['id']) ? 'selected' : '' ?>>
                        <?= $cat['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if($editProduct && $editProduct['image']): ?>
                <p>Current Image:</p>
                <img src="../img/<?= $editProduct['image'] ?>" width="80">
            <?php endif; ?>
            <input type="file" name="image">
            <button type="submit" name="<?= $editProduct ? 'update_product' : 'add_product' ?>">
                <?= $editProduct ? 'Update Product' : 'Add Product' ?>
            </button>
            <?php if($editProduct): ?>
                <a href="products.php" style="margin-left:10px;">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <!-- Products List -->
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>৳<?= $row['price'] ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><img src="../img/<?= $row['image'] ?>" alt=""></td>
                <td>
                    <a class="edit" href="products.php?edit=<?= $row['id'] ?>">Edit</a>
                    <a class="delete" href="products.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>
