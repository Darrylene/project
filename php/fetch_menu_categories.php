<?php
header('Content-Type: application/json');

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch menu categories
    $stmt = $pdo->query('SELECT menu_category_id, menu_category_name FROM menu_categories');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return categories as JSON
    echo json_encode(['success' => true, 'categories' => $categories]);
} catch (Exception $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
