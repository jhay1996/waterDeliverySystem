<?php
// Include database connection
include 'db_connect.php';

if (isset($_GET['ordernum'])) {
    $orderId = $_GET['ordernum'];

    // Query to get order items
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE ordernum = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orderItems = [];
    while ($row = $result->fetch_assoc()) {
        $orderItems[] = $row;
    }

    // Return the order items in JSON format
    echo json_encode($orderItems);
}
?>
