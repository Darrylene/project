<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'login'); // Replace 'your_database_name' with your actual database name

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if menuId is provided
if (!isset($_POST['menuId'])) {
    echo json_encode(['success' => false, 'message' => 'Menu ID is required']);
    exit;
}

$menuId = $conn->real_escape_string($_POST['menuId']);

// Delete the menu item
$sql = "DELETE FROM menuitems WHERE menu_item_id = '$menuId'"; // Replace 'menuitems' with your actual table name
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting menu item: ' . $conn->error]);
}

$conn->close();
?>
