<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
include '../db.php';

$message = "";

// ✅ Auto-create table if not exists (with new column)
$conn->query("CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method_name VARCHAR(50) UNIQUE,
    account_number VARCHAR(30),
    account_type ENUM('Personal','Agent') DEFAULT 'Personal',
    transaction_type ENUM('Send Money','Cashout') DEFAULT 'Send Money'
)");

// ✅ Ensure default rows exist
$default_methods = ['bkash','nagad','rocket','upay'];
foreach ($default_methods as $m) {
    $check = $conn->query("SELECT * FROM payment_methods WHERE method_name='$m'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO payment_methods (method_name, account_number, account_type, transaction_type)
                      VALUES ('$m','', 'Personal','Send Money')");
    }
}

// ✅ Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($default_methods as $m) {
        if (isset($_POST["save_$m"])) {
            $num = trim($_POST["{$m}_number"]);
            $type = trim($_POST["{$m}_type"]);
            $trans = trim($_POST["{$m}_trans"]);
            if (!empty($num)) {
                $stmt = $conn->prepare("UPDATE payment_methods 
                    SET account_number=?, account_type=?, transaction_type=? 
                    WHERE method_name=?");
                $stmt->bind_param("ssss", $num, $type, $trans, $m);
                $stmt->execute();
                $stmt->close();
                $message = ucfirst($m) . " information updated successfully!";
            }
        }
    }
}

// ✅ Fetch all payment info
$data = [];
$res = $conn->query("SELECT * FROM payment_methods");
while($row = $res->fetch_assoc()){
    $data[$row['method_name']] = $row;
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Methods - Admin</title>
<link rel="stylesheet" href="../admin/css/dashboard.css">
<style>
body {margin:0;font-family:'Poppins',sans-serif;background:#f8f9fa;}
.main-content {padding:20px;min-height:100vh;}
.settings-container {
    max-width:800px;margin:40px auto;background:#fff;padding:25px;
    border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.1);
}
h1,h2 {text-align:center;}
.tabs {
    display:flex;flex-wrap:wrap;gap:5px;margin-bottom:20px;
}
.tab-btn {
    flex:1;padding:12px;cursor:pointer;background:#f4f4f4;
    border:none;border-radius:6px;font-weight:bold;transition:0.3s;
}
.tab-btn.active {background:#007bff;color:#fff;}
.tab-content {display:none;}
.tab-content.active {display:block;}
.tab-content form {display:flex;flex-direction:column;}
.tab-content form label {margin-top:10px;font-weight:500;}
.tab-content form input[type="text"],
.tab-content form select {
    padding:10px;margin-top:5px;border-radius:6px;border:1px solid #ccc;
    font-size:16px;
}
.tab-content form button {
    margin-top:20px;padding:12px;background:#28a745;color:#fff;
    border:none;border-radius:6px;cursor:pointer;font-size:16px;
    transition:0.3s;
}
.tab-content form button:hover {opacity:0.85;}
.message {
    text-align:center;font-weight:bold;color:green;margin-bottom:10px;
}
.sidebar ul li a.active {
    background:#007bff;color:#fff;
}
</style>
</head>
<body>

<div class="admin-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../img/dasb.png" alt="Logo">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="coupon.php">Coupon Code</a></li>
            <li><a href="payment_gateways.php">Payment</a></li>
            <li><a href="payment_method.php" class="active">Payment Methods</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main-content">
        <div class="settings-container">
            <h2>Manage Payment Accounts</h2>
            <?php if($message) echo "<p class='message'>$message</p>"; ?>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" onclick="openTab(event, 'bkash')">Bkash</button>
                <button class="tab-btn" onclick="openTab(event, 'nagad')">Nagad</button>
                <button class="tab-btn" onclick="openTab(event, 'rocket')">Rocket</button>
                <button class="tab-btn" onclick="openTab(event, 'upay')">Upay</button>
            </div>

            <!-- Dynamic Tab Contents -->
            <?php
            function input($name, $label, $data){
                $num = htmlspecialchars($data[$name]['account_number'] ?? '');
                $type = htmlspecialchars($data[$name]['account_type'] ?? '');
                $trans = htmlspecialchars($data[$name]['transaction_type'] ?? '');
                echo "
                <div id='$name' class='tab-content ".($name=='bkash'?'active':'')."'>
                    <form method='POST'>
                        <label>$label Number</label>
                        <input type='text' name='{$name}_number' value='$num' placeholder='Enter $label Number' required>

                        <label>Account Type</label>
                        <select name='{$name}_type' required>
                            <option value='Personal' ".($type=='Personal'?'selected':'').">Personal</option>
                            <option value='Agent' ".($type=='Agent'?'selected':'').">Agent</option>
                        </select>

                        <label>Transaction Type</label>
                        <select name='{$name}_trans' required>
                            <option value='Send Money' ".($trans=='Send Money'?'selected':'').">Send Money</option>
                            <option value='Cashout' ".($trans=='Cashout'?'selected':'').">Cashout</option>
                        </select>

                        <button type='submit' name='save_$name'>Save</button>
                    </form>
                </div>
                ";
            }

            input('bkash','Bkash',$data);
            input('nagad','Nagad',$data);
            input('rocket','Rocket',$data);
            input('upay','Upay',$data);
            ?>
        </div>
    </div>
</div>

<script>
function openTab(evt, tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    const btns = document.querySelectorAll('.tab-btn');
    tabs.forEach(t => t.classList.remove('active'));
    btns.forEach(b => b.classList.remove('active'));
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>

</body>
</html>
