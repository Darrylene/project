<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Return a success response
echo json_encode(["success" => true]);
?>
