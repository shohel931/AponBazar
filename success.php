<?php 
include 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Successful</title>
</head>
<body>
    
 
 
 <style>
 body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .success-container {
            text-align: center;
            padding: 100px 20px;
        }
        .success-icon {
            color: #28a745;
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-size: 32px;
        }
        p {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .btn {
            margin-top: 25px;
            background: #28a745;
            color: #fff;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        .btn i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .success-container {
                padding: 50px 10px;
            }
            h1 {
                font-size: 24px;
            }
            p {
                font-size: 16px;
            }
            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="success-container">
        <i class="fa-solid fa-circle-check success-icon"></i>
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase. Your order has been placed successfully.</p>
        <a href="shop.php" class="btn"><i class="fa-solid fa-shop"></i> Continue Shopping</a>
    </div>

</body>
</html>