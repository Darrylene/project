<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "login"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Retrieve POST data
$menuItemId = $_POST['menuItemId'] ?? null;
$menuItemName = $_POST['menuItemName'] ?? null;
$description = $_POST['description'] ?? null;
$price = $_POST['price'] ?? null;
$imageUrl = $_POST['imageUrl'] ?? null;
$menuCategoryId = $_POST['menuCategoryId'] ?? null;

// Validate required fields
if (!$menuItemId || !$menuItemName || !$description || !$price || !$imageUrl || !$menuCategoryId) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Prepare and execute the update query
$query = "UPDATE menuitems SET menu_item_name = ?, description = ?, price = ?, image_url = ?, menu_category_id = ? WHERE menu_item_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssdssi', $menuItemName, $description, $price, $imageUrl, $menuCategoryId, $menuItemId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Menu item updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update menu item.']);
}

$stmt->close();
$conn->close();
?>
