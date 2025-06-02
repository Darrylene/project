<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoload file
require 'vendor/autoload.php';
require 'vendor/PHPMailer/src/SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get next auto-increment value for User ID
$result = $conn->query("SHOW TABLE STATUS LIKE 'user'");
$row = $result->fetch_assoc();
$nextUserId = $row['Auto_increment'];

// Get POST data
$userFirstName = $_POST['userFirstName'];
$userLastName = $_POST['userLastName'];
$userEmail = $_POST['userEmail'];
$userType = $_POST['userType'];
$userAddress = $_POST['userAddress'];
$userContact = $_POST['userContact'];
$userStatus = $_POST['userStatus'];
$passwordStatus = "Requested"; // Set PasswordStatus to Requested

// Check if email already exists
$emailCheckSql = "SELECT * FROM user WHERE Email='$userEmail'";
$emailCheckResult = $conn->query($emailCheckSql);

if ($emailCheckResult === false) {
    echo "<script>console.error('Email check query failed: " . $conn->error . "');</script>";
    $conn->close();
    exit();
}

if ($emailCheckResult->num_rows > 0) {
    echo "Email Already Exist!";
    $conn->close();
    exit();
}

// Insert user into database
$sql = "INSERT INTO user (FirstName, LastName, Email, TypeOfUser, Address, ContactNumber, AccountStatus, PasswordStatus)
        VALUES ('$userFirstName', '$userLastName', '$userEmail', '$userType', '$userAddress', '$userContact', '$userStatus', '$passwordStatus')";

if ($conn->query($sql) === TRUE) {
    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'amante.amurao@cvsu.edu.ph'; // SMTP username
        $mail->Password = 'hyoe iiaa hgmj wavd'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your_email@example.com', 'Brew & Blend');
        $mail->addAddress($userEmail, $userFirstName . ' ' . $userLastName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Created';
        $mail->Body    = 'An account has been created for you. Please complete your registration by resetting your password using the "Forgot Password" option.';

        $mail->send();
        echo json_encode(["success" => true, "userId" => $nextUserId]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo "<script>console.error('Insert query failed: " . $conn->error . "');</script>";
}

$conn->close();
?>