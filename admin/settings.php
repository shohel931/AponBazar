<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
include '../db.php';

$message = '';
$admin_id = $_SESSION['admin_id'];

// ---------- Fetch current settings ----------
$settings_result = $conn->query("SELECT * FROM settings LIMIT 1");
$settings = $settings_result->fetch_assoc();

// ---------- Update site settings ----------
if(isset($_POST['update_site'])){
    $site_name = $_POST['site_name'];
    $site_tagline = $_POST['site_tagline'];
    $theme_color = $_POST['theme_color'];

    // Handle logo upload
    $logo = $settings['logo'] ?? '';
    if(isset($_FILES['logo']) && $_FILES['logo']['name'] != ''){
        $logo_name = time().'_'.$_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], '../img/'.$logo_name);
        $logo = $logo_name;
    }

    // Handle favicon upload
    $favicon = $settings['favicon'] ?? '';
    if(isset($_FILES['favicon']) && $_FILES['favicon']['name'] != ''){
        $favicon_name = time().'_'.$_FILES['favicon']['name'];
        move_uploaded_file($_FILES['favicon']['tmp_name'], '../img/'.$favicon_name);
        $favicon = $favicon_name;
    }

    if($settings){
        $stmt = $conn->prepare("UPDATE settings SET site_name=?, site_tagline=?, logo=?, favicon=?, theme_color=? WHERE id=?");
        $stmt->bind_param("sssssi", $site_name, $site_tagline, $logo, $favicon, $theme_color, $settings['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO settings (site_name, site_tagline, logo, favicon, theme_color) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $site_name, $site_tagline, $logo, $favicon, $theme_color);
        $stmt->execute();
    }

    $message = "Site settings updated successfully.";
    $settings_result = $conn->query("SELECT * FROM settings LIMIT 1");
    $settings = $settings_result->fetch_assoc();
}

// ---------- Change Admin Password ----------
$pass_message = '';
if(isset($_POST['change_password'])){
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $admin = $conn->query("SELECT password FROM admins WHERE id=$admin_id")->fetch_assoc();
    if(password_verify($current_pass, $admin['password'])){
        if($new_pass === $confirm_pass){
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $conn->query("UPDATE admins SET password='$hashed' WHERE id=$admin_id");
            $pass_message = "Password changed successfully!";
        } else {
            $pass_message = "New password and confirm password do not match.";
        }
    } else {
        $pass_message = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Settings</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
.main-content { max-width: 800px; margin: 20px auto; }
h1 { margin-bottom: 20px; color: #333; }
.form-container { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
.form-container label { display: block; margin-bottom: 8px; font-weight: bold; }
.form-container input[type="text"],
.form-container input[type="color"],
.form-container input[type="password"],
.form-container input[type="file"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; }
.form-container button { padding: 10px 20px; border: none; border-radius: 6px; background: #007bff; color: white; cursor: pointer; }
.form-container button:hover { opacity: 0.9; }
.message { color: green; margin-bottom: 15px; }
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
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="settings.php" class="active">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Site Settings</h1>
        <div class="form-container">
            <?php if($message) echo "<p class='message'>$message</p>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Site Name</label>
                <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>

                <label>Site Tagline</label>
                <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" required>

                <label>Theme Color</label>
                <input type="color" name="theme_color" value="<?= $settings['theme_color'] ?? '#007bff' ?>">

                <label>Logo</label>
                <input type="file" name="logo">
                <?php if(!empty($settings['logo'])): ?>
                    <img src="../img/<?= $settings['logo'] ?>" width="100">
                <?php endif; ?>

                <label>Favicon</label>
                <input type="file" name="favicon">
                <?php if(!empty($settings['favicon'])): ?>
                    <img src="../img/<?= $settings['favicon'] ?>" width="50">
                <?php endif; ?>

                <button type="submit" name="update_site">Update Site Settings</button>
            </form>
        </div>

        <h1>Change Admin Password</h1>
        <div class="form-container">
            <?php if($pass_message) echo "<p class='message'>$pass_message</p>"; ?>
            <form method="POST">
                <label>Current Password</label>
                <input type="password" name="current_password" required>

                <label>New Password</label>
                <input type="password" name="new_password" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>

                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
