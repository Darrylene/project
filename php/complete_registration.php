<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $address = $_POST['address'];
  $contactNumber = $_POST['contactNumber'];
  $userType = "Customer"; // Default value for TypeOfUser

  // Create connection
  $conn = new mysqli("localhost", "root", "", "login");

  // Check connection
  if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and bind
  $stmt = $conn->prepare("INSERT INTO user (FirstName, LastName, Email, Password, TypeOfUser, Address, ContactNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    die("Prepare failed: " . $conn->error);
  }
  $stmt->bind_param("sssssss", $_SESSION['firstName'], $_SESSION['lastName'], $_SESSION['email'], $_SESSION['hashedPassword'], $userType, $address, $contactNumber);

  // Execute the statement
  if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to complete registration.']);
    exit;
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();

  // Clear session data
  session_unset();
  session_destroy();

  echo json_encode(['status' => 'success', 'message' => 'Registration completed successfully.']);
}
?>
