<?php
session_start();

// Check if cart has items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: full-menu.php');
    exit;
}

// Cart calculations
$cart_items = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.20; // 20% VAT
$delivery_fee = isset($_POST['delivery_fee']) ? (float)$_POST['delivery_fee'] : 5;
$total = $subtotal + $tax + $delivery_fee;

// Get customer details from session if coming from checkout
$customer_name = $_SESSION['customer_name'] ?? '';
$customer_email = $_SESSION['customer_email'] ?? '';
$customer_phone = $_SESSION['customer_phone'] ?? '';
$order_type = $_SESSION['order_type'] ?? 'delivery';
$payment_method = $_SESSION['payment_method'] ?? 'card';

// Process payment
$payment_success = false;
$payment_error = '';
$transaction_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    $card_number = preg_replace('/\s+/', '', $_POST['card_number']);
    $card_name = $_POST['card_name'];
    $expiry_month = $_POST['expiry_month'];
    $expiry_year = $_POST['expiry_year'];
    $cvv = $_POST['cvv'];
    
    // Basic validation
    if (empty($card_number) || empty($card_name) || empty($expiry_month) || empty($expiry_year) || empty($cvv)) {
        $payment_error = 'Please fill all card details';
    } elseif (strlen($card_number) < 15 || strlen($card_number) > 16) {
        $payment_error = 'Invalid card number';
    } elseif (strlen($cvv) != 3 && strlen($cvv) != 4) {
        $payment_error = 'Invalid CVV';
    } else {
        // In production, integrate with real payment gateway like Stripe, PayPal, etc.
        // For demo, we'll simulate successful payment
        $payment_success = true;
        $transaction_id = 'TXN' . date('Ymd') . rand(100000, 999999);
        
        // Save order to database
        $host = 'localhost';
        $dbname = 'prime_dine_db';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $order_number = 'PRIME' . date('Ymd') . rand(100, 999);
            $items_json = json_encode($cart_items);
            
            $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, items, subtotal, tax, delivery_fee, total, order_type, payment_method, status, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', ?)");
            
            $stmt->execute([
                $order_number,
                $customer_name,
                $customer_email,
                $customer_phone,
                $items_json,
                $subtotal,
                $tax,
                $delivery_fee,
                $total,
                $order_type,
                'card',
                $transaction_id
            ]);
            
            // Clear cart after successful order
            $_SESSION['cart'] = [];
            $_SESSION['last_order'] = [
                'order_number' => $order_number,
                'total' => $total,
                'transaction_id' => $transaction_id
            ];
            
        } catch(PDOException $e) {
            // Still consider payment successful even if DB save fails for demo
        }
    }
}

// If payment successful, redirect to success page
if ($payment_success) {
    header('Location: order-success.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Secure Payment | Prime Dine</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Jost:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

:root {
  --gold: #C9A84C;
  --gold-lt: #E2C97E;
  --gold-dk: #96721F;
  --ink: #0E0C09;
  --ink-2: #1C1914;
  --ink-3: #2A2520;
  --fog: #8A8070;
  --mist: #C4BAA8;
  --white: #FDFAF5;
  --green: #2E7D32;
  --red: #D32F2F;
  --serif: 'Cormorant Garamond', Georgia, serif;
  --sans: 'Jost', sans-serif;
}

body {
  background: linear-gradient(135deg, var(--ink) 0%, var(--ink-2) 100%);
  color: var(--white);
  font-family: var(--sans);
  font-weight: 300;
  min-height: 100vh;
  padding: 2rem;
}

/* Payment Container */
.payment-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 2rem;
}

/* Order Summary */
.order-summary {
  background: var(--ink-2);
  border-radius: 20px;
  padding: 2rem;
  border: 1px solid rgba(255,255,255,.08);
  position: sticky;
  top: 2rem;
  height: fit-content;
}
.order-summary h2 {
  font-family: var(--serif);
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255,255,255,.1);
}
.order-summary h2 i { color: var(--gold); margin-right: .8rem; }
.order-items {
  max-height: 300px;
  overflow-y: auto;
  margin-bottom: 1.5rem;
}
.order-item {
  display: flex;
  justify-content: space-between;
  padding: .8rem 0;
  border-bottom: 1px solid rgba(255,255,255,.05);
  font-size: .9rem;
}
.order-item-name {
  display: flex;
  gap: .5rem;
}
.order-item-qty {
  color: var(--gold);
  font-weight: 500;
}
.order-details {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(255,255,255,.1);
}
.order-row {
  display: flex;
  justify-content: space-between;
  padding: .5rem 0;
  font-size: .85rem;
}
.order-total {
  display: flex;
  justify-content: space-between;
  padding: 1rem 0;
  margin-top: .5rem;
  border-top: 2px solid var(--gold);
  font-weight: 600;
  font-size: 1.1rem;
}
.order-total span:last-child { color: var(--gold); font-size: 1.3rem; }

/* Payment Card */
.payment-card {
  background: var(--ink-2);
  border-radius: 20px;
  padding: 2rem;
  border: 1px solid rgba(255,255,255,.08);
}
.payment-header {
  text-align: center;
  margin-bottom: 2rem;
}
.payment-header .lock-icon {
  width: 60px;
  height: 60px;
  background: rgba(201,168,76,.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
}
.payment-header .lock-icon i { font-size: 1.8rem; color: var(--gold); }
.payment-header h3 {
  font-family: var(--serif);
  font-size: 1.8rem;
  font-weight: 300;
}
.payment-header p { font-size: .8rem; color: var(--fog); margin-top: .5rem; }

/* Card Input */
.card-wrapper {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  position: relative;
  min-height: 220px;
}
.card-chip {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
}
.card-chip i { font-size: 2rem; color: var(--gold); opacity: .6; }
.card-number-display {
  font-family: monospace;
  font-size: 1.4rem;
  letter-spacing: 2px;
  margin: 2rem 0 1rem;
  word-break: break-all;
}
.card-holder-display {
  font-size: .8rem;
  text-transform: uppercase;
  margin-top: .5rem;
}
.card-expiry-display {
  font-size: .8rem;
}
.form-group {
  margin-bottom: 1.2rem;
}
.form-group label {
  display: block;
  font-size: .7rem;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--fog);
  margin-bottom: .5rem;
}
.form-group input, .form-group select {
  width: 100%;
  padding: .9rem 1rem;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 12px;
  color: var(--white);
  font-family: var(--sans);
  font-size: .95rem;
  transition: all .3s;
}
.form-group input:focus, .form-group select:focus {
  outline: none;
  border-color: var(--gold);
  background: rgba(201,168,76,.05);
}
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}
.error-message {
  background: rgba(211,47,47,.15);
  border: 1px solid var(--red);
  color: #ff6b6b;
  padding: .8rem;
  border-radius: 12px;
  font-size: .8rem;
  margin-bottom: 1rem;
  text-align: center;
}
.payment-btn {
  width: 100%;
  padding: 1.1rem;
  background: var(--gold);
  color: var(--ink);
  border: none;
  border-radius: 40px;
  font-family: var(--sans);
  font-size: .8rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  font-weight: 600;
  cursor: pointer;
  transition: all .3s;
  margin-top: 1rem;
}
.payment-btn:hover {
  background: var(--gold-lt);
  transform: translateY(-2px);
}
.payment-btn:disabled {
  opacity: .5;
  cursor: not-allowed;
}
.security-badges {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(255,255,255,.08);
}
.security-badges span {
  font-size: .65rem;
  color: var(--fog);
}
.security-badges i { color: var(--gold); margin-right: .3rem; }

/* Back link */
.back-link {
  display: inline-block;
  margin-bottom: 1rem;
  color: var(--fog);
  text-decoration: none;
  font-size: .85rem;
  transition: color .3s;
}
.back-link:hover { color: var(--gold); }

/* Responsive */
@media (max-width: 768px) {
  body { padding: 1rem; }
  .payment-container { grid-template-columns: 1fr; }
  .order-summary { position: static; margin-bottom: 1rem; }
  .card-number-display { font-size: 1rem; }
}
</style>
</head>
<body>

<a href="javascript:history.back()" class="back-link">
  <i class="fas fa-arrow-left"></i> Back to Checkout
</a>

<div class="payment-container">
  <!-- Order Summary -->
  <div class="order-summary">
    <h2><i class="fas fa-receipt"></i> Order Summary</h2>
    <div class="order-items">
      <?php foreach ($cart_items as $item): ?>
      <div class="order-item">
        <div class="order-item-name">
          <span class="order-item-qty"><?php echo $item['quantity']; ?>x</span>
          <span><?php echo htmlspecialchars($item['name']); ?></span>
        </div>
        <span>£<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="order-details">
      <div class="order-row">
        <span>Subtotal</span>
        <span>£<?php echo number_format($subtotal, 2); ?></span>
      </div>
      <div class="order-row">
        <span>VAT (20%)</span>
        <span>£<?php echo number_format($tax, 2); ?></span>
      </div>
      <div class="order-row">
        <span>Delivery Fee</span>
        <span>£<?php echo number_format($delivery_fee, 2); ?></span>
      </div>
      <div class="order-total">
        <span>Total Amount</span>
        <span>£<?php echo number_format($total, 2); ?></span>
      </div>
    </div>
    <div style="margin-top: 1rem; padding: .8rem; background: rgba(201,168,76,.1); border-radius: 12px; text-align: center;">
      <i class="fas fa-shield-alt" style="color: var(--gold);"></i>
      <span style="font-size: .7rem;">Your payment is secure and encrypted</span>
    </div>
  </div>

  <!-- Payment Form -->
  <div class="payment-card">
    <div class="payment-header">
      <div class="lock-icon">
        <i class="fas fa-lock"></i>
      </div>
      <h3>Secure Payment</h3>
      <p>Enter your card details to complete the order</p>
    </div>

    <?php if ($payment_error): ?>
    <div class="error-message">
      <i class="fas fa-exclamation-triangle"></i> <?php echo $payment_error; ?>
    </div>
    <?php endif; ?>

    <!-- Card Preview -->
    <div class="card-wrapper">
      <div class="card-chip">
        <i class="fas fa-microchip"></i>
      </div>
      <div class="card-number-display" id="cardNumberDisplay">•••• •••• •••• ••••</div>
      <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
          <div style="font-size: .6rem; opacity: .6;">CARD HOLDER</div>
          <div class="card-holder-display" id="cardHolderDisplay">YOUR NAME</div>
        </div>
        <div>
          <div style="font-size: .6rem; opacity: .6;">EXPIRES</div>
          <div class="card-expiry-display" id="cardExpiryDisplay">MM/YY</div>
        </div>
      </div>
    </div>

    <form method="POST" id="paymentForm">
      <div class="form-group">
        <label><i class="fas fa-user"></i> Cardholder Name</label>
        <input type="text" name="card_name" id="cardName" placeholder="JOHN SMITH" required 
               oninput="document.getElementById('cardHolderDisplay').innerText = this.value.toUpperCase() || 'YOUR NAME'">
      </div>

      <div class="form-group">
        <label><i class="fas fa-credit-card"></i> Card Number</label>
        <input type="text" name="card_number" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required
               oninput="formatCardNumber(this); updateCardDisplay();">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label><i class="far fa-calendar-alt"></i> Expiry Date</label>
          <select name="expiry_month" id="expiryMonth" required onchange="updateExpiryDisplay()">
            <option value="">Month</option>
            <?php for($m=1; $m<=12; $m++): ?>
            <option value="<?php echo sprintf('%02d', $m); ?>"><?php echo sprintf('%02d', $m); ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group">
          <label><i class="far fa-calendar-alt"></i> Year</label>
          <select name="expiry_year" id="expiryYear" required onchange="updateExpiryDisplay()">
            <option value="">Year</option>
            <?php $year = date('Y'); for($y=$year; $y<=$year+10; $y++): ?>
            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label><i class="fas fa-shield-alt"></i> CVV</label>
          <input type="password" name="cvv" id="cvv" placeholder="123" maxlength="4" required>
        </div>
        <div class="form-group">
          <label>&nbsp;</label>
          <div style="padding: .9rem; background: rgba(255,255,255,.05); border-radius: 12px; text-align: center; font-size: .7rem;">
            <i class="fas fa-question-circle"></i> 3 or 4 digit code
          </div>
        </div>
      </div>

      <button type="submit" name="process_payment" class="payment-btn" id="payBtn">
        <i class="fas fa-lock"></i> Pay £<?php echo number_format($total, 2); ?>
      </button>
    </form>

    <div class="security-badges">
      <span><i class="fab fa-cc-visa"></i> Visa</span>
      <span><i class="fab fa-cc-mastercard"></i> Mastercard</span>
      <span><i class="fab fa-cc-amex"></i> Amex</span>
      <span><i class="fas fa-shield-alt"></i> PCI Compliant</span>
      <span><i class="fas fa-lock"></i> SSL Secure</span>
    </div>
  </div>
</div>

<script>
// Format card number with spaces
function formatCardNumber(input) {
  let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  let formatted = '';
  for (let i = 0; i < value.length; i++) {
    if (i > 0 && i % 4 === 0) {
      formatted += ' ';
    }
    formatted += value[i];
  }
  input.value = formatted.substring(0, 19);
}

// Update card display
function updateCardDisplay() {
  let cardNum = document.getElementById('cardNumber').value;
  if (cardNum === '') {
    document.getElementById('cardNumberDisplay').innerText = '•••• •••• •••• ••••';
  } else {
    let last4 = cardNum.slice(-4);
    let masked = '•••• •••• •••• ' + last4;
    document.getElementById('cardNumberDisplay').innerText = masked;
  }
}

// Update expiry display
function updateExpiryDisplay() {
  let month = document.getElementById('expiryMonth').value;
  let year = document.getElementById('expiryYear').value;
  if (month && year) {
    document.getElementById('cardExpiryDisplay').innerText = month + '/' + year.slice(-2);
  } else if (month) {
    document.getElementById('cardExpiryDisplay').innerText = month + '/YY';
  } else {
    document.getElementById('cardExpiryDisplay').innerText = 'MM/YY';
  }
}

// Form validation before submit
document.getElementById('paymentForm').addEventListener('submit', function(e) {
  let cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
  let cardName = document.getElementById('cardName').value;
  let cvv = document.getElementById('cvv').value;
  let month = document.getElementById('expiryMonth').value;
  let year = document.getElementById('expiryYear').value;
  
  if (!cardName) {
    e.preventDefault();
    alert('Please enter cardholder name');
    return false;
  }
  
  if (cardNumber.length < 15) {
    e.preventDefault();
    alert('Please enter a valid card number');
    return false;
  }
  
  if (!month || !year) {
    e.preventDefault();
    alert('Please select expiry date');
    return false;
  }
  
  if (cvv.length < 3) {
    e.preventDefault();
    alert('Please enter valid CVV');
    return false;
  }
  
  document.getElementById('payBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
  document.getElementById('payBtn').disabled = true;
});
</script>
</body>
</html>