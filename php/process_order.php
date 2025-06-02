<?php
header('Content-Type: application/json');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Retrieve the user ID from the session
$userId = intval($_SESSION['user_id']); // Ensure the ID is an integer

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login");

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Extract order details
$cart = $data['cart'];
$menuOrders = $data['menuOrders']; // New menu_orders field
$paymentMethod = $data['paymentMethod']; // Already mapped in the frontend
$orderSource = $data['orderSource']; // Should always be "Online"
$orderDate = date('Y-m-d H:i:s');

// Calculate total price and total quantity
$totalPrice = array_reduce($cart, function ($sum, $item) {
    return $sum + (floatval(str_replace('₱', '', $item['price'])) * $item['quantity']);
}, 0);

$totalQuantity = array_reduce($cart, function ($sum, $item) {
    return $sum + $item['quantity'];
}, 0);

// Insert the order into the orders table
$stmt = $conn->prepare("INSERT INTO orders (order_date, ID, menu_orders, quantity, total_price, order_source, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisidss", $orderDate, $userId, $menuOrders, $totalQuantity, $totalPrice, $orderSource, $paymentMethod);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to insert order.']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();

// Respond with success
echo json_encode(['success' => true]);
