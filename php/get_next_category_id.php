<?php
// Database connection parameters – adjust as needed
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use SHOW TABLE STATUS to get next auto-increment for the categories table
$sql = "SHOW TABLE STATUS LIKE 'categories'";
$result = $conn->query($sql);
$nextId = 0;
if ($result && $row = $result->fetch_assoc()) {
    $nextId = $row['Auto_increment'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(['success' => true, 'categoryId' => $nextId]);
// No closing PHP tag to avoid extra whitespace
