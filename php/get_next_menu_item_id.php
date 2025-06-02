<?php
header('Content-Type: application/json');

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the next menu item ID
    $stmt = $pdo->query('SELECT MAX(menu_item_id) + 1 AS next_id FROM menuitems');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the next ID
    echo json_encode(['success' => true, 'menuItemId' => $result['next_id'] ?? 1]);
} catch (Exception $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
