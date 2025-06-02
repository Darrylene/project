<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $enteredCode = $_POST['verificationCode'];

  if ($enteredCode == $_SESSION['verificationCode']) {
    echo json_encode(['status' => 'success', 'message' => 'Verification successful.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid verification code.']);
  }
}
?>
