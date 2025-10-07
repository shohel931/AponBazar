<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hash);
        $stmt->fetch();

        if (password_verify($pass, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - AponBazar</title>
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
.input-group input,
.email {
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
    background: #007bff;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    margin: 10px 0  20px;
}
button:hover {
    background: #0056b3;
}
p {
    text-align: center;
}
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>
    <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <input type="email" class="email" name="email" placeholder="Email Address" required>

        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fa-regular fa-eye" id="togglePassword"></i>
        </div>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
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
