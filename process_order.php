<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    echo "Access denied.";
    exit();
}

$buyer_id = $_SESSION['user']['id'];
$payment_method = $_POST['payment_method'] ?? '';

if (!$payment_method) {
    die("Invalid payment method.");
}

// Optional card validation
if ($payment_method === 'card') {
    $card_name = trim($_POST['card_name']);
    $card_number = trim($_POST['card_number']);
    $expiry = trim($_POST['expiry']);
    $cvv = trim($_POST['cvv']);

    if (!$card_name || !$card_number || !$expiry || !$cvv) {
        die("Please fill in all card details.");
    }

}

$stmt = $conn->prepare("SELECT product_id FROM cart WHERE buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Cart is empty.");
}

// Collect product IDs
$order_items = [];
while ($row = $result->fetch_assoc()) {
    $order_items[] = $row['product_id'];
}

$order_data = json_encode($order_items);

// Save order
$stmt = $conn->prepare("INSERT INTO orders (buyer_id, products, payment_method, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $buyer_id, $order_data, $payment_method);
$stmt->execute();

$order_id = $stmt->insert_id; // ✅ Get order ID immediately after INSERT
$stmt->close();

// Clear cart
$stmt = $conn->prepare("DELETE FROM cart WHERE buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect to invoice
header("Location: invoice.php?order_id=$order_id");
exit();
?>
