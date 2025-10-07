<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// Check if exists
$check = $conn->query("SELECT * FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");
if($check->num_rows>0){
    $conn->query("DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id");
}else{
    $conn->query("INSERT INTO wishlist(user_id,product_id) VALUES($user_id,$product_id)");
}
header("Location: ".$_SERVER['HTTP_REFERER']);
