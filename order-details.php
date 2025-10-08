<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    exit('Access denied');
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    exit('Invalid request');
}

$order_id = intval($_GET['id']);

// ✅ Get Order Info
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();

if (!$order) {
    exit('<p>Order not found!</p>');
}

// ✅ Get Order Items
$items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items = $items_stmt->get_result();
?>

<div class="order-info">
    <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
    <p><strong>Total:</strong> ৳<?= number_format($order['total'], 2) ?></p>
    <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>
    <p><strong>Order Status:</strong> <?= htmlspecialchars($order['order_status']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Date:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?></p>
</div>

<hr>

<h4>🛒 Ordered Products:</h4>
<table class="modal-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price (৳)</th>
            <th>Subtotal (৳)</th>
        </tr>
    </thead>
    <tbody>
        <?php while($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
