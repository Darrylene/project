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

// Get POST data
$userId = $_POST['userId'];

// Set deletion date to 30 days from now
$deletionDate = date('Y-m-d H:i:s', strtotime('+30 days'));

// Update user to mark for deletion
$sql = "UPDATE user SET DeletionDate='$deletionDate' WHERE ID='$userId'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Update query failed: " . $conn->error]);
}

$conn->close();
?>
