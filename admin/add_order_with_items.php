<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $status = 0; // Default status: "To be Delivered"

    $stmt = $conn->prepare("INSERT INTO orders (name, address, email, mobile, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $address, $email, $mobile, $status);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        foreach ($_POST['product_id'] as $index => $product_id) {
            $qty = $_POST['qty'][$index];
            $stmt2 = $conn->prepare("INSERT INTO order_list (order_id, product_id, qty) VALUES (?, ?, ?)");
            $stmt2->bind_param("iii", $order_id, $product_id, $qty);
            $stmt2->execute();
        }
        echo 1;
    } else {
        echo 0;
    }
}
?>
