<?php
session_start();

// Set the timezone to Singapore time
date_default_timezone_set('Asia/Singapore');

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
    $otp = $_POST['otp'];

    // Validate inputs
    if (empty($email) || empty($otp)) {
        echo "<script>alert('Email or OTP cannot be empty.'); window.location.href = 'verification code.php';</script>";
        exit();
    }

    // Validate OTP format
    if (!is_numeric($otp) || strlen($otp) != 6) {
        echo "<script>alert('Invalid OTP format. Please enter a 6-digit numeric code.'); window.location.href = 'verification code.php';</script>";
        exit();
    }

    // Check if OTP is valid
    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM user WHERE email = ?");
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
        $row = $result->fetch_assoc();
        $storedOtp = $row['otp'];
        $otpExpiry = $row['otp_expiry'];

        // Check if OTP matches and is not expired
        if ($otp === $storedOtp && strtotime($otpExpiry) > time()) {
            // OTP is valid
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>OTP Verified</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>
            <body>
                <div class='modal fade show d-block' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='true' style='background-color: rgba(0, 0, 0, 0.5);'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content' style='background-color: #8C785E; color: #f8f4ec;'>
                            <div class='modal-header' style='border-bottom: 1px solid #EAD8C0;'>
                                <h5 class='modal-title' id='successModalLabel'>Success!</h5>
                            </div>
                            <div class='modal-body text-center'>
                                <p>✅ OTP verified successfully! Redirecting to the Change Password page...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function redirectToChangePassword() {
                        window.location.href = 'change password.html';
                    }
                    setTimeout(redirectToChangePassword, 2000); // Redirect after 2 seconds
                </script>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
            </body>
            </html>";
            exit();
        } elseif (strtotime($otpExpiry) <= time()) {
            // OTP has expired
            error_log("OTP validation failed: OTP has expired.");
            echo "<script>alert('OTP has expired. Please request a new one.'); window.location.href = 'verification code.php';</script>";
            exit();
        } else {
            // OTP is invalid
            error_log("OTP validation failed: Invalid OTP.");
            echo "<script>alert('Invalid OTP. Please try again.'); window.location.href = 'verification code.php';</script>";
            exit();
        }
    } else {
        // Email not found
        error_log("OTP validation failed: Email not found.");
        echo "<script>alert('Email not found. Please try again.'); window.location.href = 'verification code.php';</script>";
        exit();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
