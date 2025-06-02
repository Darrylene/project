<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productId = $_POST['productId'];
$productName = $_POST['productName'];
$categoryId = $_POST['categoryId'];
$pricePerUnit = $_POST['pricePerUnit'];
$unit = $_POST['unit'];
$stockLevel = $_POST['stockLevel'];
$reorderLevel = $_POST['reorderLevel'];

$sql = "UPDATE products SET product_name = ?, category_id = ?, price_per_unit = ?, unit = ?, stock_level = ?, reorder_level = ? WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsiii", $productName, $categoryId, $pricePerUnit, $unit, $stockLevel, $reorderLevel, $productId);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = "Failed to update product";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
