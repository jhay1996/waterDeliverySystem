<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection variables
$servername = "localhost";
$username = "u807574647_avawaters"; // Your DB username
$password = "Avawaters123"; // Your DB password
$dbname = "u807574647_avawaters"; // Your DB name

// Get POST data
$ordernum = $_POST['ordernum'] ?? null;
$name = $_POST['name'] ?? null;
$address = $_POST['address'] ?? null;
$mobile = $_POST['mobile'] ?? null;
$items = isset($_POST['order_items']) ? json_decode($_POST['order_items'], true) : null;
$totalAmount = $_POST['totalAmount'] ?? null;
$image = isset($_FILES['image']) ? $_FILES['image'] : null;  // Handle image upload if present

// Check if all required fields are present
if ($ordernum && $name && $address && $mobile && $items && $totalAmount) {
    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Handle image upload
        $imageData = ""; // Default to empty
        if ($image && $image['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $imageName = uniqid() . "_" . basename($image['name']); // Add unique prefix to prevent overwrites
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                $imageData = $imageName; // Only store the file name in the database
            } else {
                throw new Exception("Image upload failed.");
            }
        }

        // Insert payment data into the database
        $stmt = $conn->prepare("INSERT INTO payment_details (ordernum, name, address, mobile, order_items, total_amount, image_data) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters to the query
        $stmt->bind_param("sssssss", $ordernum, $name, $address, $mobile, json_encode($items), $totalAmount, $imageData);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Update order status to "delivered"
        $updateStmt = $conn->prepare("UPDATE new_orders SET status = 'delivered' WHERE ordernum = ?");
        if (!$updateStmt) {
            throw new Exception("Prepare update failed: " . $conn->error);
        }
        $updateStmt->bind_param("s", $ordernum);
        if (!$updateStmt->execute()) {
            throw new Exception("Update execute failed: " . $updateStmt->error);
        }

        // Commit the transaction
        $conn->commit();
        
        // Send success response
        echo json_encode(["status" => "success", "message" => "Payment successfully saved and order updated."]);

    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        
        // Send failure response with error message
        echo json_encode(["status" => "success", "message" => $e->getMessage()]);
    } finally {
        // Close the connection
        $conn->close();
    }
} else {
    // Return error if required fields are missing
    echo json_encode(["status" => "success", "message" => "Required fields are missing."]);
}
?>
