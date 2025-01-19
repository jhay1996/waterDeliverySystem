<?php
// save_order.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ordernum = $_POST['ordernum'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $total_amount = $_POST['total_amount'];

    // Database connection (adjust according to your settings)
    $conn = new mysqli('localhost', 'username', 'password', 'database_name');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert query
    $sql = "INSERT INTO payment_details (ordernum, name, address, mobile, total_amount) 
            VALUES ('$ordernum', '$name', '$address', '$mobile', '$total_amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Order saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
