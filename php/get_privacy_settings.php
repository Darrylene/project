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

// Retrieve the privacy settings
$sql = "SELECT EmailNotifications, DataSharing FROM user WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'emailNotifications' => (bool)$row['EmailNotifications'],
        'dataSharing' => (bool)$row['DataSharing']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to retrieve privacy settings.']);
}

$stmt->close();
$conn->close();
?>
