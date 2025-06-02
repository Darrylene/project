<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch users from database
$sql = "SELECT ID, FirstName, LastName, Email, TypeOfUser, Address, ContactNumber, AccountStatus, PasswordStatus FROM user";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["success" => false, "message" => "Fetch query failed: " . $conn->error]);
    exit();
}

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode(["success" => true, "users" => $users]);

$conn->close();
?>