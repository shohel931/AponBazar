<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $number = trim($_POST['number']);
    $pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, number, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $number, $pass);


    if ($stmt->execute()) {
        $message = "Registration successful! You can now login.";
    } else {
        $message = "Error: This email may already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - AponBazar</title>
<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<style>
body {
    background: #f4f6f8;
    font-family: 'Poppins', sans-serif;
}
.form-container {
    max-width: 400px;
    margin: 100px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #333;
}
.input-group {
    position: relative;
}
input{
    width: 100%;
    padding: 12px 40px 12px 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
}
.input-group i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #888;
}
button {
    width: 100%;
    background: #28a745;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    margin: 10px 0 20px;
}
button:hover {
    background: #218838;
}
p {
    text-align: center;
}
.message {
    color: green;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="form-container">
    <h2>Create Account</h2>
    <?php if($message): ?><p class="message"><?= $message ?></p><?php endif; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name*" required>
        <input type="email" name="email" placeholder="Email Address*" required>
        <input type="number" name="number" placeholder="Phone number*" required>
        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required minlength="8" maxlength="20">
            <i class="fa-regular fa-eye" id="togglePassword"></i>
        </div>
        <button type="submit" name="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>


<script>
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function () {
  const type = password.getAttribute("type") === "password" ? "text" : "password";
  password.setAttribute("type", type);
  this.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>
