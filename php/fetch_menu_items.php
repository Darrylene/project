<?php
header('Content-Type: application/json');

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch menu items
    $stmt = $pdo->query('SELECT menu_item_id, menu_item_name, description, ingredients_used, price, image_url, menu_category_id FROM menuitems');
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return success response
    echo json_encode(['success' => true, 'menuItems' => $menuItems]);
} catch (Exception $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
