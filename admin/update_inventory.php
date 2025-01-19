<?php
// Include database connection
include 'db_connect.php';

if (isset($_POST['updatedQuantities'])) {
    $updatedQuantities = $_POST['updatedQuantities'];

    // Loop through the updated quantities and update the product inventory
    foreach ($updatedQuantities as $item) {
        $productId = $item['productId'];
        $qty = $item['qty'];

        // Fetch the current stock of the product
        $stmt = $conn->prepare("SELECT qty FROM product_list WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $currentStock = $row['stock'];
            $newStock = $currentStock - $qty;

            // Update the product stock
            $stmt_update = $conn->prepare("UPDATE product_list SET qty = ? WHERE id = ?");
            $stmt_update->bind_param("ii", $newStock, $productId);
            $stmt_update->execute();
        }
    }

    echo 'Inventory updated successfully';
}
?>
