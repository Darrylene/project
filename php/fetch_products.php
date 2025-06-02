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

$sql = "SELECT product_id, product_name, category_id, price_per_unit, unit, stock_level, reorder_level FROM products";
$result = $conn->query($sql);

$response = array();
if ($result->num_rows > 0) {
    $response['success'] = true;
    $response['products'] = array();
    while($row = $result->fetch_assoc()) {
        $response['products'][] = $row;
    }
} else {
    $response['success'] = false;
    $response['message'] = "No products found";
}

$conn->close();

echo json_encode($response);
?>