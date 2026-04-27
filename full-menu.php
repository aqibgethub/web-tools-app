<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'prime_dine_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $pdo = null;
}

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Menu items data
$menu_items = [
    'starters' => [
        ['id' => 1, 'name' => 'Seared Scallops', 'price' => 18, 'desc' => 'King scallops, cauliflower purée, crispy pancetta, brown butter', 'image' => 'https://images.unsplash.com/photo-1546549032-9571cd6b27df?w=400', 'category' => 'starters', 'is_veg' => false, 'prep_time' => 15],
        ['id' => 2, 'name' => 'Truffle Arancini', 'price' => 14, 'desc' => 'Crispy risotto balls, mozzarella, black truffle, parmesan foam', 'image' => 'https://images.unsplash.com/photo-1564121211835-e88c852648ab?w=400', 'category' => 'starters', 'is_veg' => true, 'prep_time' => 10],
        ['id' => 3, 'name' => 'Beef Tartare', 'price' => 19, 'desc' => 'Hand-cut Hereford beef, quail egg, cornichons, sourdough crisp', 'image' => 'https://images.unsplash.com/photo-1574487103001-e3b40ff47c84?w=400', 'category' => 'starters', 'is_veg' => false, 'prep_time' => 12],
    ],
    'mains' => [
        ['id' => 4, 'name' => 'Wagyu Ribeye', 'price' => 48, 'desc' => '28-day dry-aged, truffle pomme purée, bone marrow jus', 'image' => 'https://images.unsplash.com/photo-1600803907087-f56d462fd26b?w=400', 'category' => 'mains', 'is_veg' => false, 'prep_time' => 25],
        ['id' => 5, 'name' => 'Cornish Lamb Rack', 'price' => 42, 'desc' => 'Herb-crusted, wild garlic purée, roasted roots, red wine reduction', 'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=400', 'category' => 'mains', 'is_veg' => false, 'prep_time' => 20],
        ['id' => 6, 'name' => 'Gressingham Duck', 'price' => 38, 'desc' => 'Confit leg croquette, roasted fig, celeriac purée, port jus', 'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400', 'category' => 'mains', 'is_veg' => false, 'prep_time' => 22],
    ],
    'seafood' => [
        ['id' => 7, 'name' => 'Cornish Lobster', 'price' => 62, 'desc' => 'Grilled with garlic butter, samphire, sea herbs, crushed Jersey Royals', 'image' => 'https://images.unsplash.com/photo-1550367083-9fa5411cb303?w=400', 'category' => 'seafood', 'is_veg' => false, 'prep_time' => 30],
        ['id' => 8, 'name' => 'Sea Bass', 'price' => 34, 'desc' => 'Pan-seared, fennel purée, saffron velouté, heritage tomatoes', 'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=400', 'category' => 'seafood', 'is_veg' => false, 'prep_time' => 18],
    ],
    'vegetarian' => [
        ['id' => 9, 'name' => 'Black Truffle Risotto', 'price' => 32, 'desc' => 'Arborio rice, aged Parmigiano, wild mushrooms, truffle oil', 'image' => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?w=400', 'category' => 'vegetarian', 'is_veg' => true, 'prep_time' => 20],
        ['id' => 10, 'name' => 'Wild Mushroom Wellington', 'price' => 29, 'desc' => 'Puff pastry, duxelles, spinach, truffle cream sauce', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400', 'category' => 'vegetarian', 'is_veg' => true, 'prep_time' => 25],
    ],
    'desserts' => [
        ['id' => 11, 'name' => 'Chocolate Fondant', 'price' => 12, 'desc' => 'Warm chocolate cake, vanilla bean ice cream, raspberry coulis', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400', 'category' => 'desserts', 'is_veg' => true, 'prep_time' => 10],
        ['id' => 12, 'name' => 'Crème Brûlée', 'price' => 10, 'desc' => 'Madagascar vanilla, caramelized sugar crust, shortbread biscuit', 'image' => 'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=400', 'category' => 'desserts', 'is_veg' => true, 'prep_time' => 8],
    ],
];

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_to_cart') {
            $item_id = (int)$_POST['item_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Find item
            foreach ($menu_items as $category => $items) {
                foreach ($items as $item) {
                    if ($item['id'] === $item_id) {
                        if (isset($_SESSION['cart'][$item_id])) {
                            $_SESSION['cart'][$item_id]['quantity'] += $quantity;
                        } else {
                            $_SESSION['cart'][$item_id] = [
                                'id' => $item['id'],
                                'name' => $item['name'],
                                'price' => $item['price'],
                                'quantity' => $quantity,
                                'image' => $item['image']
                            ];
                        }
                        $success = "Added to cart!";
                    }
                    break 2;
                }
            }
        } elseif ($_POST['action'] === 'remove_from_cart') {
            $item_id = (int)$_POST['item_id'];
            unset($_SESSION['cart'][$item_id]);
        } elseif ($_POST['action'] === 'update_cart') {
            $item_id = (int)$_POST['item_id'];
            $quantity = (int)$_POST['quantity'];
            if ($quantity > 0) {
                $_SESSION['cart'][$item_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$item_id]);
            }
        } elseif ($_POST['action'] === 'clear_cart') {
            $_SESSION['cart'] = [];
        } elseif ($_POST['action'] === 'place_order') {
            // Save order to database
            if ($pdo && count($_SESSION['cart']) > 0) {
                $order_number = 'PRIME' . date('Ymd') . rand(100, 999);
                $items_json = json_encode($_SESSION['cart']);
                $subtotal = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
                $tax = $subtotal * 0.2; // 20% VAT
                $delivery_fee = $_POST['order_type'] === 'delivery' ? 5 : 0;
                $total = $subtotal + $tax + $delivery_fee;
                
                $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, items, subtotal, tax, delivery_fee, total, order_type, payment_method, special_instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $order_number,
                    $_POST['customer_name'],
                    $_POST['customer_email'],
                    $_POST['customer_phone'],
                    $items_json,
                    $subtotal,
                    $tax,
                    $delivery_fee,
                    $total,
                    $_POST['order_type'],
                    $_POST['payment_method'],
                    $_POST['special_instructions']
                ]);
                
                $_SESSION['cart'] = [];
                $order_placed = true;
                $order_details = ['order_number' => $order_number, 'total' => $total];
            }
        }
    }
}

// Cart calculations
$cart_items = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.2;
$delivery_fee = 5;
$total = $subtotal + $tax + $delivery_fee;
$cart_count = array_sum(array_column($cart_items, 'quantity'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Full Menu & Online Ordering | Prime Dine</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Jost:wght@200;300;400;500;600&display=swap" rel="stylesheet">
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
  --serif: 'Cormorant Garamond', Georgia, serif;
  --sans: 'Jost', sans-serif;
}

body {
  background: var(--ink);
  color: var(--white);
  font-family: var(--sans);
  font-weight: 300;
  line-height: 1.6;
}

/* Navigation */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  padding: 0 clamp(1rem, 5vw, 4rem);
  display: flex; align-items: center; justify-content: space-between;
  height: 80px;
  background: rgba(14,12,9,.95);
  backdrop-filter: blur(10px);
}
.nav__logo {
  font-family: var(--serif);
  font-size: clamp(1.2rem, 5vw, 1.6rem);
  font-weight: 400;
  letter-spacing: .15em;
  color: var(--white);
  text-decoration: none;
}
.nav__logo span { color: var(--gold); }
.nav__links { display: flex; gap: clamp(1rem, 3vw, 2.5rem); list-style: none; }
.nav__links a { font-size: .7rem; letter-spacing: .2em; text-transform: uppercase; color: var(--mist); text-decoration: none; transition: color .3s; }
.nav__links a:hover, .nav__links a.active { color: var(--gold); }
.nav__cart {
  position: relative;
  color: var(--white);
  text-decoration: none;
  font-size: 1.2rem;
}
.cart-count {
  position: absolute;
  top: -8px;
  right: -12px;
  background: var(--gold);
  color: var(--ink);
  font-size: .65rem;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 50%;
}
.nav__mobile-btn { display: none; background: none; border: none; color: var(--white); font-size: 1.4rem; }

/* Cart Sidebar */
.cart-sidebar {
  position: fixed;
  top: 0;
  right: -400px;
  width: 100%;
  max-width: 400px;
  height: 100vh;
  background: var(--ink-2);
  z-index: 200;
  transition: right .3s ease;
  box-shadow: -5px 0 30px rgba(0,0,0,.5);
  display: flex;
  flex-direction: column;
}
.cart-sidebar.open { right: 0; }
.cart-header {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255,255,255,.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.cart-header h3 { font-family: var(--serif); font-size: 1.5rem; }
.cart-close {
  background: none;
  border: none;
  color: var(--mist);
  font-size: 1.3rem;
  cursor: pointer;
}
.cart-items {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}
.cart-item {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.cart-item-img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 4px;
}
.cart-item-info { flex: 1; }
.cart-item-name { font-family: var(--serif); font-weight: 500; }
.cart-item-price { color: var(--gold); font-size: .8rem; }
.cart-item-qty {
  display: flex;
  align-items: center;
  gap: .5rem;
  margin-top: .5rem;
}
.cart-item-qty button {
  background: rgba(255,255,255,.1);
  border: none;
  color: var(--white);
  width: 24px;
  height: 24px;
  border-radius: 4px;
  cursor: pointer;
}
.cart-item-qty span { font-size: .85rem; }
.cart-item-remove {
  color: var(--fog);
  background: none;
  border: none;
  cursor: pointer;
}
.cart-footer {
  padding: 1.5rem;
  border-top: 1px solid rgba(255,255,255,.1);
}
.cart-total { display: flex; justify-content: space-between; margin-bottom: 1rem; font-weight: 500; }
.checkout-btn {
  width: 100%;
  padding: 1rem;
  background: var(--gold);
  color: var(--ink);
  border: none;
  font-family: var(--sans);
  font-size: .7rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  font-weight: 600;
  cursor: pointer;
  transition: background .3s;
}
.checkout-btn:hover { background: var(--gold-lt); }
.empty-cart { text-align: center; color: var(--fog); padding: 2rem; }

/* Menu Grid */
.menu-hero {
  height: 40vh; min-height: 300px;
  background: linear-gradient(rgba(14,12,9,.7), rgba(14,12,9,.9)),
              url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=2070');
  background-size: cover; background-position: center;
  display: flex; align-items: center; justify-content: center;
  text-align: center;
}
.menu-hero h1 {
  font-family: var(--serif);
  font-size: clamp(3rem, 8vw, 5rem);
  font-weight: 300;
}
.menu-hero h1 span { color: var(--gold); font-style: italic; }

.category-nav {
  padding: 2rem clamp(1rem, 5vw, 4rem);
  background: var(--ink-2);
  position: sticky;
  top: 80px;
  z-index: 50;
  overflow-x: auto;
  white-space: nowrap;
}
.category-links {
  display: inline-flex;
  gap: 2rem;
  justify-content: center;
}
.category-links a {
  color: var(--fog);
  text-decoration: none;
  font-size: .7rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  padding: .5rem 0;
  transition: color .3s;
}
.category-links a:hover,
.category-links a.active { color: var(--gold); border-bottom: 1px solid var(--gold); }

.menu-section {
  padding: 4rem clamp(1rem, 5vw, 4rem);
  max-width: 1400px;
  margin: 0 auto;
}
.menu-section-header {
  text-align: center;
  margin-bottom: 3rem;
}
.menu-section-header .eyebrow {
  font-size: .65rem;
  letter-spacing: .3em;
  text-transform: uppercase;
  color: var(--gold);
}
.menu-section-header h2 {
  font-family: var(--serif);
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 300;
}
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
}
.menu-card {
  background: var(--ink-2);
  border: 1px solid rgba(255,255,255,.05);
  border-radius: 8px;
  overflow: hidden;
  transition: transform .3s;
}
.menu-card:hover { transform: translateY(-5px); }
.menu-card-img {
  height: 220px;
  overflow: hidden;
}
.menu-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .5s;
}
.menu-card:hover .menu-card-img img { transform: scale(1.05); }
.menu-card-content { padding: 1.5rem; }
.menu-card-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: .5rem;
}
.menu-card-header h3 { font-family: var(--serif); font-size: 1.3rem; font-weight: 500; }
.menu-card-price { color: var(--gold); font-size: 1.2rem; font-family: var(--serif); }
.menu-card-desc { font-size: .8rem; color: var(--mist); margin-bottom: 1rem; line-height: 1.5; }
.menu-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.veg-badge {
  font-size: .6rem;
  color: #6B8E23;
  border: 1px solid #6B8E23;
  padding: .2rem .5rem;
  border-radius: 20px;
}
.add-to-cart {
  background: var(--gold);
  color: var(--ink);
  border: none;
  padding: .5rem 1rem;
  font-size: .65rem;
  letter-spacing: .1em;
  text-transform: uppercase;
  font-weight: 600;
  cursor: pointer;
  transition: background .3s;
  border-radius: 4px;
}
.add-to-cart:hover { background: var(--gold-lt); }

/* Checkout Modal */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,.9);
  z-index: 300;
  align-items: center;
  justify-content: center;
}
.modal.active { display: flex; }
.modal-content {
  background: var(--ink-2);
  max-width: 500px;
  width: 90%;
  padding: 2rem;
  border-radius: 12px;
  max-height: 85vh;
  overflow-y: auto;
}
.modal-content h3 { font-family: var(--serif); font-size: 1.8rem; margin-bottom: 1rem; }
.modal-content input, .modal-content select, .modal-content textarea {
  width: 100%;
  padding: .8rem;
  margin-bottom: 1rem;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  color: var(--white);
  font-family: var(--sans);
}
.modal-content label { font-size: .7rem; letter-spacing: .1em; margin-bottom: .3rem; display: block; }
.order-confirm {
  background: #2E7D32;
  color: white;
  padding: .5rem;
  border-radius: 8px;
  text-align: center;
  margin-bottom: 1rem;
}
.toast {
  position: fixed;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  background: var(--gold);
  color: var(--ink);
  padding: .8rem 1.5rem;
  border-radius: 40px;
  font-size: .8rem;
  font-weight: 500;
  z-index: 250;
  display: none;
}
.toast.show { display: block; animation: fadeInOut 3s ease; }
@keyframes fadeInOut {
  0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
  15% { opacity: 1; transform: translateX(-50%) translateY(0); }
  85% { opacity: 1; transform: translateX(-50%) translateY(0); }
  100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
}

/* Footer */
footer {
  padding: 3rem;
  background: var(--ink-2);
  text-align: center;
  border-top: 1px solid rgba(255,255,255,.06);
}
.footer-copy { font-size: .65rem; color: var(--fog); }

@media (max-width: 768px) {
  .nav__links { display: none; }
  .nav__mobile-btn { display: block; }
  .category-nav { top: 64px; }
  .menu-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<nav>
  <a href="index.php" class="nav__logo"><span>Prime</span> Dine</a>
  <ul class="nav__links">
    <li><a href="index.php">Home</a></li>
    <li><a href="full-menu.php" class="active">Order Online</a></li>
    <li><a href="index.php#about">About</a></li>
    <li><a href="index.php#contact">Contact</a></li>
  </ul>
  <a href="javascript:void(0)" class="nav__cart" id="cartIcon">
    <i class="fas fa-shopping-bag"></i>
    <span class="cart-count" id="cartCount"><?php echo $cart_count; ?></span>
  </a>
  <button class="nav__mobile-btn" id="mobileBtn">&#9776;</button>
</nav>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h3>Your Order</h3>
    <button class="cart-close" id="closeCart">&times;</button>
  </div>
  <div class="cart-items" id="cartItems">
    <?php if (empty($cart_items)): ?>
      <div class="empty-cart">Your cart is empty</div>
    <?php else: ?>
      <?php foreach ($cart_items as $item): ?>
      <div class="cart-item">
        <img src="<?php echo $item['image']; ?>" class="cart-item-img">
        <div class="cart-item-info">
          <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="cart-item-price">£<?php echo number_format($item['price'], 2); ?></div>
          <div class="cart-item-qty">
            <button class="qty-minus" data-id="<?php echo $item['id']; ?>">-</button>
            <span><?php echo $item['quantity']; ?></span>
            <button class="qty-plus" data-id="<?php echo $item['id']; ?>">+</button>
          </div>
        </div>
        <button class="cart-item-remove" data-id="<?php echo $item['id']; ?>"><i class="fas fa-trash"></i></button>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="cart-footer">
    <div class="cart-total">
      <span>Subtotal</span>
      <span>£<?php echo number_format($subtotal, 2); ?></span>
    </div>
    <div class="cart-total">
      <span>VAT (20%)</span>
      <span>£<?php echo number_format($tax, 2); ?></span>
    </div>
    <div class="cart-total">
      <span>Delivery</span>
      <span>£<?php echo number_format($delivery_fee, 2); ?></span>
    </div>
    <div class="cart-total">
      <strong>Total</strong>
      <strong>£<?php echo number_format($total, 2); ?></strong>
    </div>
    <button class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button>
  </div>
</div>

<!-- Menu Hero -->
<div class="menu-hero">
  <div>
    <h1>Order <span>Online</span></h1>
    <p>Freshly prepared • Contactless delivery • Pickup available</p>
  </div>
</div>

<!-- Category Navigation -->
<div class="category-nav">
  <div class="category-links">
    <a href="#starters" class="active">Starters</a>
    <a href="#mains">Main Courses</a>
    <a href="#seafood">Seafood</a>
    <a href="#vegetarian">Vegetarian</a>
    <a href="#desserts">Desserts</a>
  </div>
</div>

<!-- Menu Sections -->
<?php foreach ($menu_items as $category_key => $category_data): ?>
<section id="<?php echo $category_key; ?>" class="menu-section">
  <div class="menu-section-header">
    <span class="eyebrow">
      <?php 
        $titles = ['starters' => 'Begin Your Journey', 'mains' => 'The Main Event', 'seafood' => 'From The Coast', 
                   'vegetarian' => 'Plant-Based Excellence', 'desserts' => 'Sweet Finale'];
        echo $titles[$category_key] ?? ucfirst($category_key);
      ?>
    </span>
    <h2><?php echo ucfirst($category_key); ?></h2>
  </div>
  <div class="menu-grid">
    <?php foreach ($category_data as $item): ?>
    <div class="menu-card">
      <div class="menu-card-img"><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"></div>
      <div class="menu-card-content">
        <div class="menu-card-header">
          <h3><?php echo $item['name']; ?></h3>
          <span class="menu-card-price">£<?php echo $item['price']; ?></span>
        </div>
        <p class="menu-card-desc"><?php echo $item['desc']; ?></p>
        <div class="menu-card-meta">
          <?php if ($item['is_veg']): ?>
            <span class="veg-badge"><i class="fas fa-leaf"></i> Vegetarian</span>
          <?php else: ?>
            <span></span>
          <?php endif; ?>
          <button class="add-to-cart" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-price="<?php echo $item['price']; ?>" data-image="<?php echo $item['image']; ?>">
            <i class="fas fa-plus"></i> Add to Cart
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>

<!-- Checkout Modal -->
<div class="modal" id="checkoutModal">
  <div class="modal-content">
    <h3>Complete Your Order</h3>
    <form id="orderForm" method="POST">
      <input type="hidden" name="action" value="place_order">
      <label>Full Name *</label>
      <input type="text" name="customer_name" required>
      <label>Email *</label>
      <input type="email" name="customer_email" required>
      <label>Phone *</label>
      <input type="tel" name="customer_phone" required>
      <label>Order Type *</label>
      <select name="order_type" id="orderType">
        <option value="delivery">Delivery (+£5)</option>
        <option value="pickup">Pickup (Free)</option>
        <option value="dine_in">Dine In</option>
      </select>
      <label>Payment Method *</label>
      <select name="payment_method">
        <option value="card">Card (Pay on delivery/pickup)</option>
        <option value="cash">Cash</option>
        <option value="online">Online Banking</option>
      </select>
      <label>Special Instructions (optional)</label>
      <textarea name="special_instructions" rows="3" placeholder="Any allergies, preferred pickup time, etc."></textarea>
      <div class="order-confirm">
        <strong>Total Amount: £<span id="modalTotal"><?php echo number_format($total, 2); ?></span></strong>
      </div>
      <button type="submit" class="checkout-btn" style="width:100%; margin-top:0;">Place Order</button>
    </form>
    <button onclick="closeModal()" style="margin-top:1rem; background:none; border:none; color:var(--fog); cursor:pointer;">Cancel</button>
  </div>
</div>

<div class="toast" id="toast">Item added to cart!</div>

<footer>
  <div class="footer-copy">© 2025 Prime Dine. All rights reserved.</div>
</footer>

<script>
// Cart functions
let cartCount = <?php echo $cart_count; ?>;

function updateCartDisplay() {
  location.reload();
}

// Add to cart via AJAX
document.querySelectorAll('.add-to-cart').forEach(btn => {
  btn.addEventListener('click', function() {
    const formData = new FormData();
    formData.append('action', 'add_to_cart');
    formData.append('item_id', this.dataset.id);
    formData.append('quantity', 1);
    
    fetch(window.location.href, { method: 'POST', body: formData })
      .then(() => {
        document.getElementById('toast').classList.add('show');
        setTimeout(() => document.getElementById('toast').classList.remove('show'), 3000);
        setTimeout(() => location.reload(), 500);
      });
  });
});

// Cart sidebar
const cartIcon = document.getElementById('cartIcon');
const cartSidebar = document.getElementById('cartSidebar');
const closeCart = document.getElementById('closeCart');

cartIcon?.addEventListener('click', () => cartSidebar.classList.add('open'));
closeCart?.addEventListener('click', () => cartSidebar.classList.remove('open'));

// Checkout modal
const checkoutBtn = document.getElementById('checkoutBtn');
const checkoutModal = document.getElementById('checkoutModal');

// Update the checkout button to go to card.php
document.getElementById('checkoutBtn')?.addEventListener('click', () => {
  if (<?php echo $cart_count; ?> > 0) {
    window.location.href = 'card.php';
  } else {
    alert('Your cart is empty!');
  }
});

function closeModal() {
  checkoutModal.classList.remove('active');
}

// Update order total based on type
document.getElementById('orderType')?.addEventListener('change', function() {
  let subtotal = <?php echo $subtotal; ?>;
  let tax = <?php echo $tax; ?>;
  let deliveryFee = this.value === 'delivery' ? 5 : 0;
  let total = subtotal + tax + deliveryFee;
  document.getElementById('modalTotal').innerText = total.toFixed(2);
});

// Close modal on outside click
window.onclick = function(e) {
  if (e.target === checkoutModal) closeModal();
}

// Mobile menu
document.getElementById('mobileBtn')?.addEventListener('click', () => {
  alert('Use cart icon for ordering!');
});

// Smooth scroll
document.querySelectorAll('.category-links a').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
      const offset = 140;
      window.scrollTo({ top: target.offsetTop - offset, behavior: 'smooth' });
    }
    document.querySelectorAll('.category-links a').forEach(l => l.classList.remove('active'));
    link.classList.add('active');
  });
});
</script>
</body>
</html>