<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure to include the PHPMailer autoload file

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
  echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare and bind
  $stmt = $conn->prepare("SELECT ID, TypeOfUser, Password, PasswordStatus, DeletionDate FROM user WHERE Email = ?");
  $stmt->bind_param("s", $email);

  // Execute the statement
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['DeletionDate'] && strtotime($row['DeletionDate']) > time()) {
      echo json_encode(["success" => false, "message" => "Account is pending deletion."]);
      exit();
    }
    if ($row['PasswordStatus'] == 'Requested') {
      // Generate OTP and send to user's email
      $otp = rand(100000, 999999);
      $_SESSION['otp'] = $otp;
      $_SESSION['otp_email'] = $email;

      // Send OTP to user's email using PHPMailer
      $mail = new PHPMailer(true);
      try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'amante.amurao@cvsu.edu.ph'; // SMTP username
        $mail->Password = 'amanjhayarjhae071200'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your_email@example.com', 'Brew & Blend');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Brew & Blend - OTP Verification';
        $mail->Body    = "Please verify your account! Your OTP code is: $otp";

        $mail->send();
      } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        echo json_encode(["success" => false, "message" => "Error sending OTP. Please try again."]);
        exit();
      }

      // Redirect to OTP verification page
      echo json_encode(["success" => true, "redirectUrl" => "verify_otp.php"]);
      exit();
    } elseif (password_verify($password, $row['Password'])) { // Verify hashed password
      $_SESSION['user'] = $email; // Store user session
      $_SESSION['user_id'] = intval($row['ID']); // Ensure the ID is stored as an integer
      $_SESSION['user_type'] = $row['TypeOfUser']; // Store user type in session

      // Log the user type and session
      error_log("User type: " . $row['TypeOfUser']);
      error_log("Session user_id: " . $_SESSION['user_id']);

      if ($row['TypeOfUser'] == 'Admin') {
        echo json_encode(["success" => true, "redirectUrl" => "index.html"]); // Redirect to admin dashboard
      } else {
        echo json_encode(["success" => true, "redirectUrl" => "homepage.php"]); // Redirect to customer dashboard
      }
      exit();
    } else {
      error_log("Failed login attempt for email: $email"); // Log failed login attempt
      echo json_encode(["success" => false, "message" => "Invalid email or password. Please try again."]);
    }
  } else {
    error_log("Failed login attempt for email: $email"); // Log failed login attempt
    echo json_encode(["success" => false, "message" => "Invalid email or password. Please try again."]);
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();
}
?>
