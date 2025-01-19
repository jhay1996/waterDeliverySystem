<?php
// Include database connection
include 'db_connect.php';

// Mark order as delivered and reduce stock
if (isset($_POST['ordernum'])) {
    $orderNum = $_POST['ordernum'];
    $status = 'Delivered';

    // Start a transaction to ensure all updates are done together
    $conn->begin_transaction();

    try {
        // Update order status to 'Delivered'
        $stmt_order = $conn->prepare("UPDATE new_orders SET Status = ? WHERE ordernum = ?");
        $stmt_order->bind_param("si", $status, $orderNum);
        $stmt_order->execute();

        // Get order items
        $stmt_items = $conn->prepare("SELECT oi.product_id, oi.quantity FROM order_items oi WHERE oi.ordernum = ?");
        $stmt_items->bind_param("i", $orderNum);
        $stmt_items->execute();
        $result = $stmt_items->get_result();

        while ($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $orderedQty = $row['quantity'];

            // Fetch the current stock of the product
            $stmt_product = $conn->prepare("SELECT qty FROM product_list WHERE id = ?");
            $stmt_product->bind_param("i", $productId);
            $stmt_product->execute();
            $productResult = $stmt_product->get_result();

            if ($product = $productResult->fetch_assoc()) {
                $currentStock = $product['qty'];

                // Check if there’s enough stock to reduce
                if ($currentStock >= $orderedQty) {
                    // Calculate new stock level
                    $newStock = $currentStock - $orderedQty;

                    // Update the product stock in product_list
                    $stmt_update = $conn->prepare("UPDATE product_list SET qty = ? WHERE id = ?");
                    $stmt_update->bind_param("ii", $newStock, $productId);
                    $stmt_update->execute();
                } else {
                    // If there's not enough stock, throw an exception
                    throw new Exception("Not enough stock for product ID $productId.");
                }
            }
        }

        // Commit the transaction
        $conn->commit();
        echo 'Order marked as delivered and stock updated successfully';
    } catch (Exception $e) {
        // If there’s an error, rollback the transaction
        $conn->rollback();
        echo 'Error: ' . $e->getMessage();
    }
}
?>
