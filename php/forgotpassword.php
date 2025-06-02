<?php
session_start();

// Set the timezone to Singapore time
date_default_timezone_set('Asia/Singapore');

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this is the correct password for your MySQL root user
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);
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
  if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    die("Execute failed: " . $stmt->error);
  }
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Email exists, generate OTP
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 minutes

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

    // Store email in session
    $_SESSION['email'] = $email;

    // Send the OTP email using PHPMailer
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'amante.amurao@cvsu.edu.ph'; // Your Gmail email address
      $mail->Password = 'hyoe iiaa hgmj wavd'; // Your Gmail App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Enable verbose debug output
      $mail->SMTPDebug = 2; // Debug level: 2 for detailed output
      $mail->Debugoutput = function ($str, $level) {
        error_log("SMTP Debug [$level]: $str");
      };

      $mail->setFrom('no-reply@yourdomain.com', 'Brew & Blend');
      $mail->addAddress($email);

      $mail->isHTML(true);
      $mail->Subject = 'Your One-Time Pin (OTP)';
      $mail->Body = 'Your OTP is: ' . $otp . '. It will expire in 10 minutes.';

      $mail->send();

      // Fancy confirmation modal
      echo "<script>
        const modalHtml = `
          <div class='modal fade' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
              <div class='modal-content' style='background-color: #8C785E; color: #f8f4ec;'>
                <div class='modal-header' style='border-bottom: 1px solid #EAD8C0;'>
                  <h5 class='modal-title' id='successModalLabel'>Success!</h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' style='background-color: #EAD8C0;' onclick='window.location.href=\"verification code.php\";'></button>
                </div>
                <div class='modal-body text-center'>
                  <p>✅ A One-Time Pin has been sent to your email.</p>
                </div>
                <div class='modal-footer' style='border-top: 1px solid #EAD8C0;'>
                  <button type='button' class='btn custom-btn' data-bs-dismiss='modal' onclick='window.location.href=\"verification code.php\";'>OK</button>
                </div>
              </div>
            </div>
          </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      </script>";
    } catch (Exception $e) {
      error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
      die("Failed to send email. Error: {$mail->ErrorInfo}");
    }
  } else {
    echo "<script>alert('Email not found. Please try again.'); window.location.href = 'forgotpass.html';</script>";
  }

  $stmt->close();
  $conn->close();
}
?>