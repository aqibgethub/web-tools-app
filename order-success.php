<?php
session_start();

// Check if order was just placed
if (!isset($_SESSION['last_order'])) {
    header('Location: full-menu.php');
    exit;
}

$order = $_SESSION['last_order'];
$order_number = $order['order_number'];
$total = $order['total'];
$transaction_id = $order['transaction_id'];

// Clear the session variable after displaying
unset($_SESSION['last_order']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation | Prime Dine</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Jost:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
:root {
  --gold: #C9A84C;
  --gold-lt: #E2C97E;
  --ink: #0E0C09;
  --ink-2: #1C1914;
  --white: #FDFAF5;
  --green: #2E7D32;
  --serif: 'Cormorant Garamond', Georgia, serif;
  --sans: 'Jost', sans-serif;
}
body {
  background: linear-gradient(135deg, var(--ink) 0%, var(--ink-2) 100%);
  color: var(--white);
  font-family: var(--sans);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}
.success-container {
  text-align: center;
  max-width: 600px;
}
.success-icon {
  width: 100px;
  height: 100px;
  background: rgba(46,125,50,.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
}
.success-icon i { font-size: 3rem; color: #4CAF50; }
h1 { font-family: var(--serif); font-size: 2.5rem; margin-bottom: 1rem; }
.order-details {
  background: var(--ink-2);
  border-radius: 20px;
  padding: 2rem;
  margin: 2rem 0;
  border: 1px solid rgba(255,255,255,.1);
}
.order-details p { margin: .5rem 0; }
.btn-home {
  display: inline-block;
  padding: 1rem 2rem;
  background: var(--gold);
  color: var(--ink);
  text-decoration: none;
  border-radius: 40px;
  font-weight: 600;
  letter-spacing: .1em;
  margin-top: 1rem;
}
</style>
</head>
<body>
<div class="success-container">
  <div class="success-icon">
    <i class="fas fa-check-circle"></i>
  </div>
  <h1>Order Confirmed!</h1>
  <p>Thank you for your order. We've received your payment and will prepare your delicious meal.</p>
  
  <div class="order-details">
    <p><strong>Order Number:</strong> <?php echo $order_number; ?></p>
    <p><strong>Transaction ID:</strong> <?php echo $transaction_id; ?></p>
    <p><strong>Total Paid:</strong> £<?php echo number_format($total, 2); ?></p>
    <p><strong>Status:</strong> <span style="color: #4CAF50;">Confirmed ✓</span></p>
  </div>
  
  <p>You will receive a confirmation email shortly with your order details.</p>
  <a href="full-menu.php" class="btn-home">Continue Shopping</a>
</div>
</body>
</html>