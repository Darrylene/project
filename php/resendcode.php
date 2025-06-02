<?php
session_start();

// Set the timezone to Singapore time
date_default_timezone_set('Asia/Singapore');

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
  $email = $_POST['email'];

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
  }

  // Check if email exists
  $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    die("Prepare failed: " . $conn->error);
  }
  $stmt->bind_param("s", $email);

  // Execute the statement
  if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    die("Execute failed: " . $stmt->error);
  }
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Email exists, generate OTP
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 minutes

    // Debugging: Log the generated OTP and expiry time
    error_log("Generated OTP: " . $otp);
    error_log("OTP Expiry: " . $expiry);

    // Save the OTP and expiry in the database
    $stmt = $conn->prepare("UPDATE user SET otp = ?, otp_expiry = ? WHERE email = ?");
    if (!$stmt) {
      error_log("Prepare failed: " . $conn->error);
      die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $otp, $expiry, $email);

    if (!$stmt->execute()) {
      error_log("Execute failed: " . $stmt->error);
      die("Execute failed: " . $stmt->error);
    }

    // Send the OTP email using PHPMailer
    $mail = new PHPMailer(true);
    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
      $mail->SMTPAuth = true;
      $mail->Username = 'amante.amurao@cvsu.edu.ph'; // Your Gmail email address
      $mail->Password = 'hyoe iiaa hgmj wavd'; // Your Gmail App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Enable verbose debug output
      $mail->SMTPDebug = 2; // Set to 2 for detailed debug output
      $mail->Debugoutput = 'html';

      // Recipients
      $mail->setFrom('no-reply@yourdomain.com', 'Brew & Blend');
      $mail->addAddress($email);

      // Content
      $mail->isHTML(true);
      $mail->Subject = 'Your One-Time Pin (OTP)';
      $mail->Body = 'Your OTP is: ' . $otp . '. It will expire in 10 minutes.';

      $mail->send();
      echo "OTP has been sent to your email.";
    } catch (Exception $e) {
      error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
      die("Failed to send email. Error: {$mail->ErrorInfo}");
    }
  } else {
    // Email does not exist
    echo "<script>alert('Email not found. Please try again.'); window.location.href = 'forgotpass.html';</script>";
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();
}
?>
