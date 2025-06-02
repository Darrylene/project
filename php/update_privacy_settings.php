<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['emailNotifications']) || !isset($data['dataSharing'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
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

// Update the privacy settings
$emailNotifications = $data['emailNotifications'] ? 1 : 0;
$dataSharing = $data['dataSharing'] ? 1 : 0;

$sql = "UPDATE user SET EmailNotifications = ?, DataSharing = ? WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $emailNotifications, $dataSharing, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Privacy settings updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update privacy settings.']);
}

$stmt->close();
$conn->close();
?>
