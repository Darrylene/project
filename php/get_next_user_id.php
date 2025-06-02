<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "<script>console.error('Connection failed: " . $conn->connect_error . "');</script>";
    exit();
}

// Get next auto-increment value for User ID
$result = $conn->query("SHOW TABLE STATUS LIKE 'user'");
if (!$result) {
    echo "<script>console.error('Query failed: " . $conn->error . "');</script>";
    exit();
}
$row = $result->fetch_assoc();
$nextUserId = $row['Auto_increment'];

echo json_encode(["success" => true, "userId" => $nextUserId]);

$conn->close();
?>
