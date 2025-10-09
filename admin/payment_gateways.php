<?php
// admin/payment_gateways.php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php'; // adjust path if needed

$message = '';

// Handle Add
if(isset($_POST['add_gateway'])){
    $title = $conn->real_escape_string($_POST['title']);
    $type = $conn->real_escape_string($_POST['type']);
    $bkash = $conn->real_escape_string($_POST['bkash_no'] ?: null);
    $nagad = $conn->real_escape_string($_POST['nagad_no'] ?: null);
    $rocket = $conn->real_escape_string($_POST['rocket_no'] ?: null);
    $upay = $conn->real_escape_string($_POST['upay_no'] ?: null);
    $desc = $conn->real_escape_string($_POST['description'] ?: null);
    $active = isset($_POST['active']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO payment_gateways (title, type, bkash_no, nagad_no, rocket_no, upay_no, description, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $title, $type, $bkash, $nagad, $rocket, $upay, $desc, $active);
    if($stmt->execute()){
        $message = "✅ Payment gateway added successfully.";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM payment_gateways WHERE id=$id");
    header("Location: payment_gateways.php");
    exit;
}

// Handle Toggle Active
if(isset($_GET['toggle'])){
    $id = intval($_GET['toggle']);
    // fetch current
    $cur = $conn->query("SELECT active FROM payment_gateways WHERE id=$id")->fetch_assoc();
    $new = $cur ? (1 - intval($cur['active'])) : 1;
    $stmt = $conn->prepare("UPDATE payment_gateways SET active=? WHERE id=?");
    $stmt->bind_param("ii", $new, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: payment_gateways.php");
    exit;
}

// Handle Edit - load existing
$editing = false;
$edit_item = null;
if(isset($_GET['edit'])){
    $editing = true;
    $eid = intval($_GET['edit']);
    $edit_item = $conn->query("SELECT * FROM payment_gateways WHERE id=$eid")->fetch_assoc();
}

// Handle Update
if(isset($_POST['update_gateway'])){
    $id = intval($_POST['gateway_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $type = $conn->real_escape_string($_POST['type']);
    $bkash = $conn->real_escape_string($_POST['bkash_no'] ?: null);
    $nagad = $conn->real_escape_string($_POST['nagad_no'] ?: null);
    $rocket = $conn->real_escape_string($_POST['rocket_no'] ?: null);
    $upay = $conn->real_escape_string($_POST['upay_no'] ?: null);
    $desc = $conn->real_escape_string($_POST['description'] ?: null);
    $active = isset($_POST['active']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE payment_gateways SET title=?, type=?, bkash_no=?, nagad_no=?, rocket_no=?, upay_no=?, description=?, active=? WHERE id=?");
    $stmt->bind_param("sssssssii", $title, $type, $bkash, $nagad, $rocket, $upay, $desc, $active, $id);
    if($stmt->execute()){
        $message = "✅ Payment gateway updated successfully.";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
    header("Location: payment_gateways.php");
    exit;
}

// Fetch all gateways
$gateways = $conn->query("SELECT * FROM payment_gateways ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin - Payment Gateways</title>
<link rel="stylesheet" href="../css/admin.css"> <!-- optional -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
<style>
/* Simple admin styles (included inline so you have CSS) */
body{font-family:Inter,Segoe UI,Roboto,Arial; background:#f4f6f8; margin:0; padding:20px;}
.container{max-width:1100px;margin:0 auto;}
.header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.header h1{font-size:20px;color:#333;}
.message{padding:10px;border-radius:6px;margin-bottom:15px;}
.success{background:#e6ffed;color:#155724;border:1px solid #c3f0d7;}
.error{background:#fff0f0;color:#8b1c1c;border:1px solid #f5c2c2;}

.grid{display:grid;grid-template-columns: 1fr 380px; gap:18px; align-items:start;}
.card{background:#fff;border-radius:10px;padding:18px;box-shadow:0 6px 18px rgba(16,24,40,0.06);}

form .row{display:flex;gap:10px;margin-bottom:10px;}
form label{display:block;font-size:13px;color:#555;margin-bottom:6px;}
input[type="text"], select, textarea{width:100%;padding:10px;border:1px solid #e6e9ee;border-radius:8px;font-size:14px;}
textarea{min-height:90px;resize:vertical;}
.btn{background:#0ea5a4;color:#fff;padding:10px 12px;border-radius:8px;border:none;cursor:pointer;}
.btn:hover{opacity:0.95;}
.small{font-size:13px;color:#666;margin-top:6px;}
.table{width:100%;border-collapse:collapse;margin-top:10px;}
.table th, .table td{padding:10px 8px;border-bottom:1px solid #eef2f6;text-align:left;font-size:14px;}
.table th{background:#f8fafc;color:#334155;font-weight:600;}
.actions a{margin-right:8px;text-decoration:none;color:#0ea5a4;}
.badge{display:inline-block;padding:6px 8px;border-radius:6px;font-size:13px;}
.active{background:#e6ffed;color:#047857;border:1px solid #b7f5d0;}
.inactive{background:#fff0f0;color:#991b1b;border:1px solid #f6c6c6;}
@media (max-width:880px){ .grid{grid-template-columns:1fr;} }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1><i class="fa-solid fa-credit-card"></i> Payment Gateways</h1>
    <div><a href="dashboard.php" class="btn"><i class="fa-solid fa-arrow-left"></i> Dashboard</a></div>
  </div>

  <?php if($message): ?>
    <div class="message success"><?= $message ?></div>
  <?php endif; ?>

  <div class="grid">
    <!-- Left: list -->
    <div class="card">
      <h3>Configured Gateways</h3>
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Title / Type</th><th>Numbers</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php if($gateways->num_rows > 0): ?>
            <?php while($g = $gateways->fetch_assoc()): ?>
              <tr>
                <td>#<?= $g['id'] ?></td>
                <td>
                  <strong><?= htmlspecialchars($g['title']) ?></strong><br>
                  <small class="small"><?= htmlspecialchars($g['type']) ?></small>
                </td>
                <td>
                  <?php if($g['bkash_no']): ?><div><strong>bKash:</strong> <?= htmlspecialchars($g['bkash_no']) ?></div><?php endif; ?>
                  <?php if($g['nagad_no']): ?><div><strong>Nagad:</strong> <?= htmlspecialchars($g['nagad_no']) ?></div><?php endif; ?>
                  <?php if($g['rocket_no']): ?><div><strong>Rocket:</strong> <?= htmlspecialchars($g['rocket_no']) ?></div><?php endif; ?>
                  <?php if($g['upay_no']): ?><div><strong>Uppay:</strong> <?= htmlspecialchars($g['upay_no']) ?></div><?php endif; ?>
                </td>
                <td>
                  <?php if($g['active']): ?>
                    <span class="badge active">Active</span>
                  <?php else: ?>
                    <span class="badge inactive">Inactive</span>
                  <?php endif; ?>
                </td>
                <td class="actions">
                  <a href="?edit=<?= $g['id'] ?>" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                  <a href="?toggle=<?= $g['id'] ?>" title="Toggle Active"><i class="fa-solid fa-power-off"></i></a>
                  <a href="?delete=<?= $g['id'] ?>" title="Delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash" style="color:#c02424;"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5">No payment gateways configured yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Right: add / edit form -->
    <div class="card">
      <?php if($editing && $edit_item): ?>
        <h3>Edit Gateway (#<?= $edit_item['id'] ?>)</h3>
        <form method="POST">
          <input type="hidden" name="gateway_id" value="<?= $edit_item['id'] ?>">
          <label>Title</label>
          <input type="text" name="title" value="<?= htmlspecialchars($edit_item['title']) ?>" required>
          <label>Type</label>
          <select name="type" required>
            <option value="Mobile Wallet" <?= $edit_item['type']=='Mobile Wallet'?'selected':'' ?>>Mobile Wallet</option>
            <option value="Bank Account" <?= $edit_item['type']=='Bank Account'?'selected':'' ?>>Bank Account</option>
            <option value="Cash" <?= $edit_item['type']=='Cash'?'selected':'' ?>>Cash</option>
            <option value="Other" <?= $edit_item['type']=='Other'?'selected':'' ?>>Other</option>
          </select>

          <label>bKash Number</label>
          <input type="text" name="bkash_no" value="<?= htmlspecialchars($edit_item['bkash_no']) ?>">

          <label>Nagad Number</label>
          <input type="text" name="nagad_no" value="<?= htmlspecialchars($edit_item['nagad_no']) ?>">

          <label>Rocket Number</label>
          <input type="text" name="rocket_no" value="<?= htmlspecialchars($edit_item['rocket_no']) ?>">

          <label>Uppay Number</label>
          <input type="text" name="upay_no" value="<?= htmlspecialchars($edit_item['upay_no']) ?>">

          <label>Description</label>
          <textarea name="description"><?= htmlspecialchars($edit_item['description']) ?></textarea>

          <label><input type="checkbox" name="active" <?= $edit_item['active']?'checked':'' ?>> Active</label>

          <div style="margin-top:10px;">
            <button type="submit" name="update_gateway" class="btn">Update Gateway</button>
            <a href="payment_gateways.php" class="btn" style="background:#6b7280;margin-left:8px;">Cancel</a>
          </div>
        </form>

      <?php else: ?>
        <h3>Add New Gateway</h3>
        <form method="POST">
          <label>Title</label>
          <input type="text" name="title" placeholder="e.g. Mobile Wallets" required>

          <label>Type</label>
          <select name="type" required>
            <option value="Mobile Wallet">Mobile Wallet</option>
            <option value="Bank Account">Bank Account</option>
            <option value="Cash">Cash</option>
            <option value="Other">Other</option>
          </select>

          <label>bKash Number</label>
          <input type="text" name="bkash_no" placeholder="017XXXXXXXX">

          <label>Nagad Number</label>
          <input type="text" name="nagad_no" placeholder="017XXXXXXXX">

          <label>Rocket Number</label>
          <input type="text" name="rocket_no" placeholder="017XXXXXXXX">

          <label>Uppay Number</label>
          <input type="text" name="upay_no" placeholder="017XXXXXXXX">

          <label>Description</label>
          <textarea name="description" placeholder="Optional description for this payment method..."></textarea>

          <label><input type="checkbox" name="active" checked> Active</label>

          <div style="margin-top:10px;">
            <button type="submit" name="add_gateway" class="btn">Add Gateway</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>

</div>
</body>
</html>
