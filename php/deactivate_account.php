<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login");

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Update the AccountStatus to 'deactivated'
$sql = "UPDATE user SET AccountStatus = 'Deactivated' WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Account successfully deactivated.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to deactivate account.']);
}

$stmt->close();
$conn->close();
?>
