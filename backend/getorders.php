<?php
header('Content-Type: application/json');

// Enable CORS headers for cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "u807574647_avawaters"; // Your DB username
$password = "Avawaters123"; // Your DB password
$dbname = "u807574647_avawaters"; // Your DB name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
}

// SQL query to get all orders with order items and product details
$sql = "
    SELECT o.*, oi.*, p.name AS product_name, p.price AS product_price, p.img_path 
    FROM new_orders o
    JOIN order_items oi ON o.ordernum = oi.ordernum
    JOIN product_list p ON oi.product_id = p.id
"; 

// Execute the query
$result = $conn->query($sql);

// Check if any orders were found
if ($result->num_rows > 0) {
    // Store all orders in an array
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $order_num = $row['ordernum'];

        // If this order doesn't exist yet in the $orders array, create it
        if (!isset($orders[$order_num])) {
            $orders[$order_num] = [
                'ordernum' => $order_num,
                'Name' => $row['Name'],
                'Address' => $row['Address'],
                'Mobile' => $row['Mobile'],
                'Email' => $row['Email'],
                'Status' => $row['Status'],
                'orderDate' => $row['orderDate'],
                'totalAmount' => 0, // We'll calculate this later
                'items' => []
            ];
        }

        // Calculate the total amount for the order
        $orderTotal = $row['quantity'] * $row['product_price'];
        $orders[$order_num]['totalAmount'] += $orderTotal;

        // Add the product item to the 'items' array for the current order
        $orders[$order_num]['items'][] = [
            'name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'price' => $row['product_price'],
            'amount' => $orderTotal,
            'img_path' => $row['img_path'],
        ];
    }

    // Convert orders array to a simple list and return the orders as a JSON response
    echo json_encode(['success' => true, 'orders' => array_values($orders)]);
} else {
    // If no orders are found
    echo json_encode(['success' => false, 'error' => 'No orders found']);
}

// Close the database connection
$conn->close();
?>
