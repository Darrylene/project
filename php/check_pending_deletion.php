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

$email = $_POST['email'];

// Check for pending deletion
$sql = "SELECT DeletionDate FROM user WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['DeletionDate'] && strtotime($row['DeletionDate']) > time()) {
        echo json_encode(["pendingDeletion" => true]);
    } else {
        echo json_encode(["pendingDeletion" => false]);
    }
} else {
    echo json_encode(["pendingDeletion" => false]);
}

$stmt->close();
$conn->close();
?>
