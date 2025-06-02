<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'];
$new_status = $data['new_status'];

$response = ["success" => false];

if ($new_status === "In-Transit") {
    // Fetch the delivery address and customer name from the user table
    $sql = "SELECT Address, CONCAT(FirstName, ' ', LastName) AS CustName FROM user WHERE ID = (SELECT ID FROM orders WHERE order_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $delivery_address = $row['Address'];
    $customer_name = $row['CustName'];

    // Insert into deliveries table with delivery_status as "Pending"
    $sql = "INSERT INTO deliveries (order_id, delivery_address, delivery_status, delivery_date, CustName) VALUES (?, ?, 'Pending', NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $order_id, $delivery_address, $customer_name);
    if ($stmt->execute()) {
        // Update the order_status in the orders table
        $sql = "UPDATE orders SET order_status = 'In-Transit' WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            $response["success"] = true;
        }
    }
} elseif ($new_status === "Cancelled") {
    // Update the order_status in the orders table
    $sql = "UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $response["success"] = true;
    }
}

echo json_encode($response);
$conn->close();
?>
