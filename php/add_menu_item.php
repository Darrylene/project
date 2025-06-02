<?php
header('Content-Type: application/json');

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve POST data
    $menuItemId = $_POST['menuItemId'];
    $menuItemName = $_POST['menuItemName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $imageUrl = $_POST['imageUrl'];
    $menuCategoryId = $_POST['menuCategoryId']; // This comes from the dropdown

    // Validate that menu_category_id exists in the menu_categories table
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM menu_categories WHERE menu_category_id = ?');
    $stmt->execute([$menuCategoryId]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid menu category ID.']);
        exit;
    }

    // Insert the new menu item into the database (omit ingredients_used)
    $stmt = $pdo->prepare('
        INSERT INTO menuitems (menu_item_id, menu_item_name, description, price, image_url, menu_category_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $menuItemId,
        $menuItemName,
        $description,
        $price,
        $imageUrl,
        $menuCategoryId
    ]);

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Menu item added successfully']);
} catch (Exception $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
