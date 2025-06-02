<?php
// Start the session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = ""; // Ensure this is the correct password for your MySQL root user
$dbname = "login";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Specify the error log file
ini_set('error_log', 'c:/xampp/php/logs/php_error_log');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form-based password update (e.g., for forgot password)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'], $_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['otp_email'] ?? null;

    if (!$email) {
        echo "<script>alert('Session expired. Please try again.'); window.location.href = 'log in.html';</script>";
        exit();
    }

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the database
        $stmt = $conn->prepare("UPDATE user SET Password = ?, PasswordStatus = 'Not Requested' WHERE Email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully. Please log in with your new password.'); window.location.href = 'log in.html';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.'); window.location.href = 'change_password.html';</script>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = 'change_password.html';</script>";
    }
    exit();
}

// Handle API-based password update (e.g., for account settings)
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST)) {
    header('Content-Type: application/json'); // Ensure JSON response for API-based requests

    try {
        // Parse the JSON request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (!isset($data['newPassword'], $data['reenterPassword'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit();
        }

        $new_password = $data['newPassword'];
        $confirm_password = $data['reenterPassword'];

        // Check if passwords match
        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Get the user ID from the session
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in.']);
            exit();
        }

        $user_id = $_SESSION['user_id'];

        // Update the user's password in the database
        $sql = "UPDATE user SET Password = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
            exit();
        }

        $stmt->bind_param("si", $hashed_password, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
    exit();
}
?>
