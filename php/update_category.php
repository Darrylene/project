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

$categoryId = $_POST['categoryId'];
$categoryName = $_POST['categoryName'];

$sql = "UPDATE categories SET category_name='$categoryName' WHERE category_id='$categoryId'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update category: ' . $conn->error]);
}

$conn->close();
?>
