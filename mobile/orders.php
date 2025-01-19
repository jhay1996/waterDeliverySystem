<?php
// Database connection
$servername = "localhost";
$username = "u807574647_avawaters"; // Your DB username
$password = "Avawaters123"; // Your DB password
$dbname = "u807574647_avawaters"; // Your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for the action (fetch orders or update order status)
$action = $_POST['action'];

if ($action == 'get_orders') {
    // Fetch orders
    $query = "SELECT * FROM orders";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode(['status' => 'success', 'orders' => $orders]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No orders found']);
    }

} elseif ($action == 'update_status') {
    // Update order status
    $order_id = $_POST['id']; // Make sure 'id' is used in the request from React Native
    $status = $_POST['status'];
    
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $status, $order_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Order status updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update order status']);
    }
}

// Close the connection
$conn->close();
?>
