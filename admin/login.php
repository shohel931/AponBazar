<?php
session_start();
include '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_pass);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_pass)) {
        $_SESSION['admin_id'] = $id;
        header("Location: index.php");
        exit;
    } else {
        $message = "ব্যবহারকারীর নাম অথবা পাসওয়ার্ড ভুল।";
    }
}
?>
<link rel="stylesheet" href="../admin/css/login.css">
<div class="login">
    <form method="POST">
    <h2>Admin Login</h2>
    <p><?= $message ?></p>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <p>Back to <a href="../index.php">Home Page</a></p>
</form>

</div>
