<?php
include 'db_connect.php';

$order_data = $_POST;
$name = $order_data['name'];
$address = $order_data['address'];
$email = $order_data['email'];
$mobile = $order_data['mobile'];

$conn->query("INSERT INTO orders (name, address, email, mobile, status) VALUES ('$name', '$address', '$email', '$mobile', 0)");
$order_id = $conn->insert_id;

foreach ($order_data['product_id'] as $index => $product_id) {
    $qty = $order_data['qty'][$index];
    $product = $conn->query("SELECT price, qty FROM product_list WHERE id = $product_id")->fetch_assoc();
    $total = $qty * $product['price'];
    $new_qty = $product['qty'] - $qty;

    $conn->query("INSERT INTO order_sales (order_id, product_id, qty, total) VALUES ($order_id, $product_id, $qty, $total)");
    $conn->query("UPDATE product_list SET qty = $new_qty WHERE id = $product_id");
}

echo 'success';
?>
