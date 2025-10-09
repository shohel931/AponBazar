<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Initialize session name for header update
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = $user['name'];
}

$message = '';

// Update profile
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
    if ($stmt->execute()) {
        $message = "✅ Profile updated successfully!";
        $user['name'] = $name;
        $user['email'] = $email;
        $_SESSION['user_name'] = $name; // Update header name
    } else {
        $message = "❌ Failed to update profile!";
    }
}

// Change password
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $user['password'])) {
        $message = "❌ Current password is incorrect!";
    } elseif ($new !== $confirm) {
        $message = "❌ New password and confirm password do not match!";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id=$user_id");
        $message = "✅ Password changed successfully!";
    }
}

// Notification settings
if (isset($_POST['update_notifications'])) {
    $email_notify = isset($_POST['email_notify']) ? 1 : 0;
    $sms_notify = isset($_POST['sms_notify']) ? 1 : 0;
    $conn->query("UPDATE users SET email_notify=$email_notify, sms_notify=$sms_notify WHERE id=$user_id");
    $message = "✅ Notification settings updated!";
}

// Delete account
if (isset($_POST['delete_account'])) {
    // Delete user record
    $conn->query("DELETE FROM users WHERE id=$user_id");
    // Destroy session and redirect
    session_destroy();
    header("Location: index.php?account_deleted=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - AponBazar</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/header.css">
<style>
.settings-container {
    max-width: 800px;
    margin: 50px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}
.settings-container h2 {
    text-align: center;
    margin-bottom: 20px;
}
.message {
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
    color: green;
}
.tabs {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    gap: 5px;
}
.tab-btn {
    flex: 1;
    padding: 12px;
    cursor: pointer;
    background: #f4f4f4;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}
.tab-btn.active {
    background: #007bff;
    color: #fff;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.tab-content form {
    display: flex;
    flex-direction: column;
}
.tab-content form label {
    margin-top: 10px;
    font-weight: 500;
}
.tab-content form input[type="text"],
.tab-content form input[type="email"],
.tab-content form input[type="password"] {
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}
.tab-content form input[type="checkbox"] {
    margin-right: 8px;
}
.tab-content form button {
    margin-top: 20px;
    padding: 12px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}
.tab-content form button:hover {
    opacity: 0.85;
}
/* Delete Account button */
.tab-content form button[name="delete_account"] {
    background: #dc3545;
}
</style>
</head>
<body>

<?php include 'includs/header.php'; ?>

<div class="settings-container">
    <h2>Account Settings</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="openTab(event, 'profile')">Profile</button>
        <button class="tab-btn" onclick="openTab(event, 'password')">Change Password</button>
        <button class="tab-btn" onclick="openTab(event, 'notifications')">Notifications</button>
        <button class="tab-btn" onclick="openTab(event, 'delete')">Delete Account</button>
    </div>

    <!-- Profile Tab -->
    <div id="profile" class="tab-content active">
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit" name="update_profile">Update Profile</button>
        </form>
    </div>

    <!-- Password Tab -->
    <div id="password" class="tab-content">
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

    <!-- Notifications Tab -->
    <div id="notifications" class="tab-content">
        <form method="POST">
            <label>
                <input type="checkbox" name="email_notify" <?= ($user['email_notify'] ?? 0) ? 'checked' : '' ?>>
                Email Notifications
            </label>
            <label>
                <input type="checkbox" name="sms_notify" <?= ($user['sms_notify'] ?? 0) ? 'checked' : '' ?>>
                SMS Notifications
            </label>
            <button type="submit" name="update_notifications">Update Notifications</button>
        </form>
    </div>

    <!-- Delete Account Tab -->
    <div id="delete" class="tab-content">
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone!');">
            <h3 style="color:red;">Delete Your Account</h3>
            <p>Once you delete your account, all your data will be permanently removed.</p>
            <button type="submit" name="delete_account">Delete Account</button>
        </form>
    </div>
</div>

<?php include 'includs/footer.php'; ?>

<script>
function openTab(evt, tabName) {
    let tabContents = document.getElementsByClassName("tab-content");
    for (let i=0; i<tabContents.length; i++) {
        tabContents[i].classList.remove("active");
    }
    let tabBtns = document.getElementsByClassName("tab-btn");
    for (let i=0; i<tabBtns.length; i++) {
        tabBtns[i].classList.remove("active");
    }
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}
</script>
<script src="js/header.js"></script>

</body>
</html>
