<?php 
// Database connection
$conn = new mysqli('localhost', 'u807574647_avawaters', 'Avawaters123', 'u807574647_avawaters');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all orders
$sql = "SELECT * FROM new_orders";
$result = $conn->query($sql);

$orders = [];

if ($result->num_rows > 0) {
    // Fetch all orders and store in an array
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    // Send the orders as a JSON response
    echo json_encode(["status" => "success", "orders" => $orders]);
} else {
    echo json_encode(["status" => "error", "message" => "No orders found"]);
}

$conn->close();
?>
