<?php
include '../db.php';
session_start();

// Optional admin check
// if (!isset($_SESSION['is_admin'])) { header("Location: login.php"); exit; }

$message = '';

// Add Slider
if (isset($_POST['add_slider'])) {
    $link = $_POST['link'];
    $imageName = $_FILES['image']['name'];
    $targetDir = "../uploads/sliders/";
    $targetFile = $targetDir . basename($imageName);

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO sliders (image, link) VALUES (?, ?)");
        $stmt->bind_param("ss", $imageName, $link);
        $stmt->execute();
        $message = "✅ Slider added successfully!";
    } else {
        $message = "❌ Image upload failed!";
    }
}

// Delete Slider
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $slider = $conn->query("SELECT * FROM sliders WHERE id=$id")->fetch_assoc();
    if ($slider) {
        unlink("../uploads/sliders/" . $slider['image']);
        $conn->query("DELETE FROM sliders WHERE id=$id");
        $message = "🗑️ Slider deleted successfully!";
    }
}

// Update Slider
if (isset($_POST['update_slider'])) {
    $id = $_POST['id'];
    $link = $_POST['link'];
    $imageName = $_FILES['image']['name'];

    if ($imageName) {
        $targetDir = "../uploads/sliders/";
        $targetFile = $targetDir . basename($imageName);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $conn->query("UPDATE sliders SET image='$imageName', link='$link' WHERE id=$id");
    } else {
        $conn->query("UPDATE sliders SET link='$link' WHERE id=$id");
    }
    $message = "✏️ Slider updated successfully!";
}

$sliders = $conn->query("SELECT * FROM sliders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Sliders</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<link rel="stylesheet" href="../admin/css/slider.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<style>
.edit-form {
  background: #f9f9f9;
  padding: 10px;
  border-radius: 8px;
  margin-top: 10px;
}
.edit-form input[type='text'] {
  width: 100%;
  padding: 5px;
  margin-bottom: 5px;
}
</style>
</head>
<body>

<div class="admin-container">

<div class="sidebar">
        <img src="../img/dasb.png" alt="">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="slider.php" class="active">Add Slider</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>


<h2 class="title">🖼️ Manage Sliders</h2>
<p class="msg"><?= $message ?></p>

<div class="form-container">
  <form method="POST" enctype="multipart/form-data">
    <label>Upload Slider Image:</label>
    <input type="file" name="image" required>

    <label>Slider Link (Optional):</label>
    <input type="text" name="link" placeholder="https://aponbazar.com/offer">

    <button type="submit" name="add_slider">Add Slider</button>
  </form>
</div>

<hr>

<h3>All Sliders</h3>
<div class="slider-list">
<?php while($row = $sliders->fetch_assoc()): ?>
  <div class="slider-item">
    <img src="../uploads/sliders/<?= htmlspecialchars($row['image']) ?>" alt="Slider" />
    <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank"><?= htmlspecialchars($row['link']) ?></a>

    <div class="action-buttons">
      <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this slider?')">Delete</a>
      <button class="edit-btn" onclick="toggleEdit(<?= $row['id'] ?>)">Edit</button>
    </div>

    <form class="edit-form" id="editForm<?= $row['id'] ?>" method="POST" enctype="multipart/form-data" style="display:none;">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <label>Update Image:</label>
      <input type="file" name="image">

      <label>Update Link:</label>
      <input type="text" name="link" value="<?= htmlspecialchars($row['link']) ?>">

      <button type="submit" name="update_slider">Update</button>
    </form>
  </div>
<?php endwhile; ?>
</div>
</div>

<script>
function toggleEdit(id) {
  const form = document.getElementById('editForm' + id);
  form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>

</body>
</html>
