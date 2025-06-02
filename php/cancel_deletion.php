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
$email = $_POST['email'];

// Cancel deletion by setting DeletionDate to NULL
$sql = "UPDATE user SET DeletionDate=NULL WHERE Email='$email'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Update query failed: " . $conn->error]);
}

$conn->close();
?>
