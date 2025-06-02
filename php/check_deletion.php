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

// Delete users whose DeletionDate has passed
$sql = "DELETE FROM user WHERE DeletionDate IS NOT NULL AND DeletionDate <= NOW()";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Delete query failed: " . $conn->error]);
}

$conn->close();
?>
