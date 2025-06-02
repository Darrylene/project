<?php
// filepath: c:\xampp\htdocs\PHP\update_user.php
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

// Get POST data
$userId = $_POST['userId'];
$userFirstName = $_POST['userFirstName'];
$userLastName = $_POST['userLastName'];
$userEmail = $_POST['userEmail'];
$userType = $_POST['userType'];
$userAddress = $_POST['userAddress'];
$userContact = $_POST['userContact'];
$userStatus = $_POST['userStatus'];
$passwordStatus = $_POST['passwordStatus'];

// Check if email already exists for another user
$emailCheckSql = "SELECT * FROM user WHERE Email='$userEmail' AND ID != '$userId'";
$emailCheckResult = $conn->query($emailCheckSql);

if ($emailCheckResult === false) {
    echo "<script>console.error('Email check query failed: " . $conn->error . "');</script>";
    $conn->close();
    exit();
}

if ($emailCheckResult->num_rows > 0) {
    echo "<script>console.error('Email already exists');</script>";
    $conn->close();
    exit();
}

// Validate userType to ensure it matches allowed values
$allowedUserTypes = ['Customer', 'Admin', 'Employee', 'Rider'];
if (!in_array($userType, $allowedUserTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid user type."]);
    $conn->close();
    exit();
}

// Update user in database
$sql = "UPDATE user SET FirstName='$userFirstName', LastName='$userLastName', Email='$userEmail', TypeOfUser='$userType', Address='$userAddress', ContactNumber='$userContact', AccountStatus='$userStatus', PasswordStatus='$passwordStatus' WHERE ID='$userId'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo "<script>console.error('Update query failed: " . $conn->error . "');</script>";
}

$conn->close();
?>