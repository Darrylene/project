<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure you have installed PHPMailer via Composer

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
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $userType = "Customer"; // Default value for TypeOfUser

  // Validate first name
  if (strlen($firstName) < 3) {
    die("First name must be at least 3 characters long.");
  }

  // Validate last name
  if (strlen($lastName) < 3) {
    die("Last name must be at least 3 characters long.");
  }

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
  }

  // Check if email already exists
  $stmt = $conn->prepare("SELECT Email FROM user WHERE Email = ?");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    die("Prepare failed: " . $conn->error);
  }
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    die("Email already exists.");
  }
  $stmt->close();

  // Validate password
  $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
  if (!preg_match($passwordPattern, $password)) {
    die("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
  }

  // Confirm password
  if ($password !== $confirmPassword) {
    die("Passwords do not match.");
  }

  // Hash the password before storing it
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Generate a verification code
  $verificationCode = rand(100000, 999999);

  // Send verification email using PHPMailer
  $mail = new PHPMailer(true);
  try {
    $mail->SMTPDebug = 2; // Enable detailed debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'amante.amurao@cvsu.edu.ph'; // SMTP username
    $mail->Password = 'amanjhayarjhae071200'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('no-reply@yourdomain.com', 'Brew & Blend Cafe');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification Code';
    $mail->Body    = "Your verification code is: $verificationCode";

    $mail->send();
  } catch (Exception $e) {
    error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    echo json_encode(['status' => 'error', 'message' => "Failed to send verification email. Mailer Error: {$mail->ErrorInfo}"]);
    exit;
  }

  // Store the verification code and registration data in the session
  $_SESSION['verificationCode'] = $verificationCode;
  $_SESSION['firstName'] = $firstName;
  $_SESSION['lastName'] = $lastName;
  $_SESSION['email'] = $email;
  $_SESSION['hashedPassword'] = $hashedPassword;
  $_SESSION['userType'] = $userType;

  echo json_encode(['status' => 'success', 'message' => 'Verification code sent to your email.']);
}
?>

