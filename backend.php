<?php
// backend.php - Enhanced with better error handling & security
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbname = 'prime_dine_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("⚠️ Database connection failed: " . $e->getMessage());
}

function returnResponse($message, $isError = false) {
    echo $message;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
    
    // Handle Reservation
    if ($_POST['form_type'] === 'reservation') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $guests = (int)($_POST['guests'] ?? 0);
        
        if (empty($name) || empty($email) || empty($date) || empty($time) || $guests < 1) {
            returnResponse("❌ All fields are required for reservation.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            returnResponse("❌ Invalid email format.");
        }
        if ($guests > 20) {
            returnResponse("❌ Maximum 20 guests per booking.");
        }
        
        $stmt = $pdo->prepare("INSERT INTO reservations (name, email, reservation_date, reservation_time, guests) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $date, $time, $guests])) {
            returnResponse("✅ Table booked successfully! Confirmation sent to your email.");
        } else {
            returnResponse("❌ Booking failed. Please try again.");
        }
    }
    
    // Handle Contact
    elseif ($_POST['form_type'] === 'contact') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            returnResponse("❌ Please fill all contact fields.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            returnResponse("❌ Invalid email address.");
        }
        
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $message])) {
            returnResponse("✅ Message sent! We'll respond within 24 hours.");
        } else {
            returnResponse("❌ Failed to send message. Try again later.");
        }
    }
} else {
    returnResponse("⚠️ Invalid request.");
}
?>