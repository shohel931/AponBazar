<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 20px;
    overflow-x: auto;
}
h1 {
    margin-bottom: 15px;
    color: #333;
}
table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}
table thead {
    background: #007bff;
    color: rgb(68, 68, 68);
}
table th, table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
table tr:hover {
    background: #f8f9fa;
}
.action-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}
.action-btn.delete {
    background: #dc3545;
}
.action-btn:hover {
    opacity: 0.85;
}
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
            <li><a href="categories.php">Catagorys</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php" class="active">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Manage Users</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th> <!-- ✅ New Column -->
                        <th>Role</th>
                        <th>Date Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
                    $i = 1;
                    while($row = $users->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['number'] ?? 'N/A') ?></td> <!-- ✅ Show Phone -->
                        <td><?= isset($row['role']) ? $row['role'] : 'Customer' ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <button class="action-btn delete" onclick="deleteUser(<?= $row['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function deleteUser(id){
    if(confirm("Are you sure you want to delete this user?")){
        window.location.href = "users.php?delete=" + id;
    }
}
</script>

<?php
// Delete User Functionality
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    echo "<script>alert('User deleted successfully'); window.location='users.php';</script>";
}
?>
</body>
</html>
