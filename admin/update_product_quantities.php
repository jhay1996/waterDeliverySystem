<?php
// Include database connection
include 'db_connect.php';

// Check if request is POST and contains necessary data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    // Fetch all order items for the given order
    $stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE ordernum = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop through each order item and update the product quantity
    while ($item = $result->fetch_assoc()) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];

        // Update product quantity in the `product_list`
        $updateStmt = $conn->prepare("UPDATE product_list SET qty = qty - ? WHERE id = ?");
        $updateStmt->bind_param("ii", $quantity, $productId);
        $updateStmt->execute();
    }

    // Optionally: You could check if the update was successful
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
