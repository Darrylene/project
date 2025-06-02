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

$sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'products'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dbname);
$stmt->execute();
$stmt->bind_result($nextProductId);
$stmt->fetch();

$response = array();
if ($nextProductId) {
    $response['success'] = true;
    $response['productId'] = $nextProductId;
} else {
    $response['success'] = false;
    $response['message'] = "Failed to fetch next Product ID";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
