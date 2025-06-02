<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Get the user ID
$user_id = $_SESSION['user_id'];

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['firstName'], $data['lastName'], $data['email'], $data['address'], $data['contactNumber'])) {
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

// Update user information
$sql = "UPDATE user SET FirstName = ?, LastName = ?, Email = ?, Address = ?, ContactNumber = ? WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssi",
    $data['firstName'],
    $data['lastName'],
    $data['email'],
    $data['address'],
    $data['contactNumber'],
    $user_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update information.']);
}

$stmt->close();
$conn->close();
?>
