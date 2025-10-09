<?php
// payment_gateway/bkash.php
include 'db.php';
session_start();

// Ensure order_id provided
if (!isset($_GET['order_id'])) {
    header("Location: ../checkout.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Invalid order ID.";
    exit;
}

// Auto-add transaction_id column if doesn't exist
$checkCol = $conn->query("SHOW COLUMNS FROM `orders` LIKE 'transaction_id'");
if ($checkCol->num_rows == 0) {
    $conn->query("ALTER TABLE `orders` ADD COLUMN `transaction_id` VARCHAR(255) NULL AFTER `discount`");
}

// 🟢 Fetch saved bKash details from settings table (admin panel)
$bkash_q = $conn->query("SELECT * FROM payment_methods WHERE method_name = 'bkash' LIMIT 1");
$bkash_info = $bkash_q->fetch_assoc();
$bkash_number = $bkash_info['account_number'] ?? '017XXXXXXXX';
$bkash_type = $bkash_info['account_type'] ?? 'Personal';
$transaction_type = $bkash_info['transaction_type'] ?? 'Send Money'; // ✅ Added Transaction Type

// Handle form submit (user submits bKash trx id)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trx_id = trim($conn->real_escape_string($_POST['trx_id'] ?? ''));
    $paid_phone = trim($conn->real_escape_string($_POST['paid_phone'] ?? ''));

    if (empty($trx_id) || strlen($trx_id) < 3) {
        $message = '<div class="error">অনুগ্রহ করে সঠিক transaction ID দিন।</div>';
    } else {
        $stmt = $conn->prepare("UPDATE orders SET payment_method = ?, payment_status = 'Pending', transaction_id = ?, updated_at = NOW() WHERE id = ?");
        $method = 'bKash';
        $stmt->bind_param("ssi", $method, $trx_id, $order_id);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            header("Location: success.php?order_id=" . $order_id);
            exit;
        } else {
            $message = '<div class="error">Payment update করা যায়নি — পরে আবার চেষ্টা করুন।</div>';
        }
    }
}
?>
<!doctype html>
<html lang="bn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BKash Payment - AponBazar</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root{
    --bg:#f7f8fb;
    --card:#ffffff;
    --accent:#0d3b66;
    --muted:#6b7280;
  }
  *{box-sizing:border-box;font-family: "Noto Sans Bengali", "Poppins", sans-serif}
  body{margin:0;background:var(--bg);padding:40px 20px;color:#111}
  .wrap{max-width:1100px;margin:20px auto;display:grid;grid-template-columns:1fr 420px;gap:30px;align-items:start}
  .card{background:var(--card);border-radius:12px;padding:24px;box-shadow:0 8px 30px rgba(13,59,102,0.06)}
  .left .brand{display:flex;gap:16px;align-items:center;margin-bottom:6px}
  .brand .logo{width:68px;height:68px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:26px}
  .brand .logo img{width:100%;height:100%;object-fit:contain;border-radius:12px}
  .brand h3{margin:0;font-size:18px;color:var(--accent)}
  .timer{background:var(--accent);color:#fff;padding:10px 14px;border-radius:8px;display:inline-block;font-weight:700;margin-left:auto}
  .order-box{margin-top:14px;padding:14px;background:#fbfdff;border-radius:10px;border:1px solid rgba(13,59,102,0.04)}
  .order-box p{margin:8px 0;color:#333}
  .big-amount{font-size:28px;font-weight:800;color:#222}
  .muted{color:var(--muted);font-size:14px}
  .form-row{margin-top:16px;display:flex;gap:10px}
  .input{flex:1;display:flex;align-items:center;border:1px solid #e6e9ef;padding:10px;border-radius:8px;background:#fff}
  .copy-btn{background:#f3f6fb;border:0;padding:8px 10px;border-radius:6px;cursor:pointer;margin-left:8px}
  .copy-btn i{color:var(--accent)}
  .label{font-size:13px;color:var(--muted);margin-bottom:6px}
  .tx-input{width:100%;padding:12px;border-radius:8px;border:1px solid #e6e9ef}
  .submit-btn{display:inline-block;background:var(--accent);color:#fff;padding:12px 20px;border-radius:8px;border:0;cursor:pointer;font-weight:700;margin-top:12px}
  .error{background:#fff0f0;color:#9a2a22;padding:10px;border-radius:6px;margin-top:10px}
  @media (max-width:980px){
    .wrap{grid-template-columns:1fr;padding:0 16px}
  }
</style>
</head>
<body>

<div class="wrap">
  <div class="card left">
    <div class="brand" style="justify-content:space-between">
      <div style="display:flex;gap:12px;align-items:center">
        <div class="logo"><img src="img/bkashl.png" alt=""></div>
        <div>
          <h3>BKash Payment</h3>
          <div class="small">অ্যাকাউন্ট টাইপ: <strong><?= htmlspecialchars($bkash_type) ?></strong></div>
        </div>
      </div>
      <div class="timer" id="countdown">15:00</div>
    </div>

    <div class="order-box">
      <div class="flex-between" style="display:flex;justify-content:space-between;align-items:flex-end">
        <div>
          <div class="small">পরিমাণ:</div>
          <div class="big-amount">৳<?= number_format($order['total'],2) ?></div>
        </div>
        <div style="text-align:right">
          <div class="small">অর্ডার আইডি</div>
          <div style="font-weight:700">#<?= $order['id'] ?></div>
        </div>
      </div>

      <div style="margin-top:14px" class="label">প্রদত্ত bKash নাম্বার</div>
      <div class="form-row">
        <div class="input">
          <div style="font-weight:700;color:#0d3b66"><?= htmlspecialchars($bkash_number) ?></div>
        </div>
        <button class="copy-btn" data-copy="<?= htmlspecialchars($bkash_number) ?>"><i class="fa-regular fa-copy"></i></button>
      </div>

      <div style="margin-top:14px" class="label">ট্রানজেকশন আইডি</div>
      <input type="text" id="trx_id" name="trx_id" class="tx-input" placeholder="TXN123456789..." form="bkashForm" required>

      <div style="margin-top:12px" class="label">আপনার bKash নম্বর (ঐচ্ছিক)</div>
      <input type="text" id="paid_phone" name="paid_phone" class="tx-input" placeholder="01XXXXXXXXX" form="bkashForm">

      <?php if (!empty($message)) echo $message; ?>

      <form id="bkashForm" method="post">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
        <input type="hidden" name="trx_id" id="hidden_trx">
        <input type="hidden" name="paid_phone" id="hidden_phone">
        <button type="submit" class="submit-btn" onclick="prepareSubmit(event)">জমা দিন</button>
      </form>
    </div>
  </div>

  <div class="card right">
    <h3>করণীয় ধাপসমূহ:</h3>
    <ul>
      <li>১) উপরের নাম্বারে <?= htmlspecialchars($transaction_type) ?> করুন (<?= htmlspecialchars($bkash_type) ?> Account)।</li>
      <li>২) Reference এ <strong>Order #<?= $order['id'] ?></strong> দিন।</li>
      <li>৩) তারপর নিচে Transaction ID লিখে “জমা দিন” ক্লিক করুন।</li>
    </ul>
  </div>
</div>

<script>
let duration = 15 * 60;
const el = document.getElementById('countdown');
setInterval(() => {
  if (duration <= 0) { el.innerText = 'সময় শেষ'; return; }
  duration--;
  const m = Math.floor(duration/60).toString().padStart(2,'0');
  const s = (duration%60).toString().padStart(2,'0');
  el.innerText = m + ':' + s;
}, 1000);

document.querySelectorAll('.copy-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const text = btn.getAttribute('data-copy');
    navigator.clipboard.writeText(text).then(() => {
      btn.innerHTML = '<i class="fa-solid fa-check" style="color:green"></i>';
      setTimeout(()=> btn.innerHTML = '<i class="fa-regular fa-copy"></i>', 2000);
    });
  });
});

function prepareSubmit(e){
  e.preventDefault();
  const trx = document.getElementById('trx_id').value.trim();
  const phone = document.getElementById('paid_phone').value.trim();
  if(!trx || trx.length < 3){
    alert('অনুগ্রহ করে সঠিক Transaction ID দিন।');
    return;
  }
  document.getElementById('hidden_trx').value = trx;
  document.getElementById('hidden_phone').value = phone;
  document.getElementById('bkashForm').submit();
}
</script>
</body>
</html>
