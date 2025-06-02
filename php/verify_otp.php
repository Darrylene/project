<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp'])) {
    // Handle form-based OTP verification (e.g., for forgot password)
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        // OTP is correct, redirect to change password page
        header("Location: change_password.html");
        exit();
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href = 'verify_otp.php';</script>";
    }
    exit();
}

// Handle API-based OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST)) {
    header('Content-Type: application/json'); // Ensure JSON response for API-based OTP verification

    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    try {
        // Get the OTP from the JSON request body
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['otp'])) {
            echo json_encode(['success' => false, 'message' => 'OTP is missing.']);
            exit();
        }

        $entered_otp = $data['otp'];

        // Check if the OTP matches the one stored in the session
        if (!isset($_SESSION['otp'])) {
            echo json_encode(['success' => false, 'message' => 'No OTP found in session.']);
            exit();
        }

        if ($entered_otp == $_SESSION['otp']) {
            // OTP is correct
            unset($_SESSION['otp']); // Clear the OTP from the session
            echo json_encode(['success' => true, 'message' => 'OTP verified successfully.']);
        } else {
            // OTP is incorrect
            echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f4ec;
    }
    .container {
      background-color: #8C785E;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-label {
      color: #f8f4ec;
    }
    .form-control {
      border: 2px solid #EAD8C0;
      border-radius: 15px;
    }
    .form-control:focus {
      outline: none;
      border-color: #f8f4ec;
      box-shadow: 0 0 10px #7c6246;
    }
    .btn-primary {
      background-color: #EAD8C0;
      color: #5b4422;
      border-radius: 15px;
    }
    .btn-primary:hover {
      background-color: #d2c8a2;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center text-custom">Verify OTP</h2>
    <form method="POST" action="verify_otp.php">
      <div class="mb-3">
        <label for="otp" class="form-label">OTP</label>
        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
