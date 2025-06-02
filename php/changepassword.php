<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; // Ensure this is the correct password for your MySQL root user
$dbname = "login";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  error_log("Connection failed: " . $conn->connect_error);
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];

  // Validate new password
  if (strlen($newPassword) < 8) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters long."]);
    exit();
  }

  // Confirm password
  if (empty($confirmPassword)) {
    echo json_encode(["status" => "error", "message" => "Please confirm your password."]);
    exit();
  }

  // Check if passwords match
  if ($newPassword !== $confirmPassword) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
    exit();
  }

  // Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // Use email from session
  $email = $_SESSION['email'];
  $passwordStatus = "Not Requested";

  // Update the password in the database
  $stmt = $conn->prepare("UPDATE user SET password = ?, PasswordStatus = ? WHERE email = ?");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(["status" => "error", "message" => "Database error."]);
    exit();
  }
  $stmt->bind_param("sss", $hashedPassword, $passwordStatus, $email);

  if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(["status" => "error", "message" => "Failed to update password."]);
    exit();
  }

  echo json_encode(["status" => "success", "message" => "Password has been successfully changed!"]);

  // Close statement and connection
  $stmt->close();
  $conn->close();
}
?>