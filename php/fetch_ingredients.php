<?php
header('Content-Type: application/json');

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch ingredients
    $stmt = $pdo->query('SELECT ingredient_id, ingredient_name FROM ingredients');
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return ingredients as JSON
    echo json_encode(['success' => true, 'ingredients' => $ingredients]);
} catch (Exception $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
