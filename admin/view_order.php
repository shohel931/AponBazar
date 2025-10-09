<?php
include '../db.php';
$id = intval($_GET['id']); // নিরাপদভাবে আইডি নিচ্ছে

// ✅ অর্ডার ইনফো ফেচ
$sql = "SELECT o.*, u.name AS user_name, o.address
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    echo "<p>❌ Order not found.</p>";
    exit;
}

echo "<h3>🧍 Customer: {$order['user_name']}</h3>";
echo "<p><strong>📍 Address:</strong> {$order['address']}</p>";
echo "<p><strong>💳 Payment:</strong> {$order['payment_method']} ({$order['transaction_id']})</p>";

echo "<h4>🛒 Products:</h4>";

// ✅ অর্ডারের প্রোডাক্ট ফেচ
$productSQL = "SELECT p.name, p.image, op.quantity, op.price 
               FROM order_products op 
               JOIN products p ON op.product_id = p.id 
               WHERE op.order_id = ?";
$stmt2 = $conn->prepare($productSQL);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$result = $stmt2->get_result();

$total = 0;
while($item = $result->fetch_assoc()){
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    echo "
    <div class='product-item'>
        <img src='../uploads/{$item['image']}' alt=''>
        <div>
            <p><strong>{$item['name']}</strong></p>
            <p>Qty: {$item['quantity']} | ৳{$item['price']} x {$item['quantity']} = ৳{$subtotal}</p>
        </div>
    </div>";
}
echo "<hr><p><strong>💰 Total:</strong> ৳{$total}</p>";
?>
