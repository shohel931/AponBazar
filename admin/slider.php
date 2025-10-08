<?php
include '../db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link'];
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    if (!is_dir("../uploads")) {
        mkdir("../uploads", 0777, true); // যদি uploads ফোল্ডার না থাকে, তৈরি করবে
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $conn->query("INSERT INTO sliders (image, link) VALUES ('$image', '$link')");
        header("Location: slider.php?success=1");
        exit;
    } else {
        header("Location: slider.php?error=1");
        exit;
    }
}
$sliders = $conn->query("SELECT * FROM sliders ORDER BY id DESC");
if (isset($_GET['success'])) {
    $message = "Slider added successfully!";
} elseif (isset($_GET['error'])) {
    $message = "Failed to add slider. Please try again.";
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Sliders</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<link rel="stylesheet" href="../admin/css/slider.css">
</head>
<body>
<div class="admin-container">
    

<!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Catagorys</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="slider.php" class="active">Add Slider</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Sliders</h1>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Slider Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <label>Slider Link (optional):</label>
            <input type="text" name="link" placeholder="https://example.com">

            <button type="submit">Add Slider</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $sliders->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?= $row['image'] ?>" alt="slider" width="120"></td>
                        <td><?= $row['link'] ? "<a href='{$row['link']}' target='_blank'>{$row['link']}</a>" : '—' ?></td>
                        <td>
                            <a href="delete_slider.php?id=<?= $row['id'] ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
