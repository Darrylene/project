<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $address = $_POST['address'];
  $contactNumber = $_POST['contactNumber'];

  // Create connection
  $conn = new mysqli("localhost", "root", "", "login");

  // Check connection
  if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
  }

  // Update address and contact number
  $stmt = $conn->prepare("UPDATE user SET Address = ?, ContactNumber = ? WHERE Email = ?");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    die("Prepare failed: " . $conn->error);
  }
  $stmt->bind_param("sss", $address, $contactNumber, $_SESSION['email']);

  // Execute the statement
  if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to update address and contact number.']);
    exit;
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();

  echo json_encode(['status' => 'success', 'message' => 'Address and contact number updated successfully.']);
}
?>
