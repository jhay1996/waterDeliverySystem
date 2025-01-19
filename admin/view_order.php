<?php
// Include database connection
include 'db_connect.php';

// Get the order number from the GET request
$order_id = isset($_GET['ordernum']) ? intval($_GET['ordernum']) : 0;

// Ensure that the order_id is valid
if ($order_id > 0) {
    // Fetch the order details
    $order_query = $conn->prepare("SELECT o.*, SUM(oi.amount) AS total_amount 
                                   FROM new_orders o 
                                   LEFT JOIN order_items oi ON o.ordernum = oi.ordernum 
                                   WHERE o.ordernum = ? 
                                   GROUP BY o.ordernum");
    $order_query->bind_param("i", $order_id);
    $order_query->execute();
    $order_result = $order_query->get_result();

    if ($order = $order_result->fetch_assoc()) {
        // Display order details
        echo "<h4>Order Details</h4>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($order['Name']) . "</p>";
        echo "<p><strong>Address:</strong> " . htmlspecialchars($order['Address']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($order['Email']) . "</p>";
        echo "<p><strong>Mobile:</strong> " . htmlspecialchars($order['Mobile']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($order['Status']) . "</p>";
        echo "<p><strong>Order Date:</strong> " . $order['orderdate'] . "</p>";
        echo "<p><strong>Total Amount:</strong> ₱" . number_format($order['total_amount'], 2) . "</p>";

        // Fetch order items
        $items_query = $conn->prepare("SELECT oi.*, p.name, p.price 
                                      FROM order_items oi 
                                      JOIN product_list p ON oi.product_id = p.id 
                                      WHERE oi.ordernum = ?");
        $items_query->bind_param("i", $order_id);
        $items_query->execute();
        $items_result = $items_query->get_result();

        echo "<h5>Ordered Items:</h5>";
        echo "<ul>";
        while ($item = $items_result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($item['name']) . "</strong>: " . $item['quantity'] . " x ₱" . number_format($item['price'], 2) . " = ₱" . number_format($item['amount'], 2) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    echo "<p>Invalid order ID.</p>";
}
?>
