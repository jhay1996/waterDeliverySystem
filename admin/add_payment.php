<?php
// Include database connection
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $ordernum = $_POST['ordernum'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $total_amount = $_POST['total_amount'];
    $order_items = $_POST['order_items'];
    $image_data = $_POST['image_data']; // Can handle image as base64 or file path

    // Insert data into the database
    $sql = "INSERT INTO payment_details (ordernum, name, address, mobile, total_amount, order_items, image_data) 
            VALUES ('$ordernum', '$name', '$address', '$mobile', '$total_amount', '$order_items', '$image_data')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
</head>
<body>
    <h1>Add Payment Details</h1>
    <form method="POST" action="add_payment.php">
        <label for="ordernum">Order Number:</label><br>
        <input type="text" name="ordernum" required><br><br>

        <label for="name">Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" name="address" required><br><br>

        <label for="mobile">Mobile:</label><br>
        <input type="text" name="mobile" required><br><br>

        <label for="total_amount">Total Amount:</label><br>
        <input type="text" name="total_amount" required><br><br>

        <label for="order_items">Order Items:</label><br>
        <textarea name="order_items" required></textarea><br><br>

        <label for="image_data">Image Data:</label><br>
        <input type="text" name="image_data" required><br><br>

        <button type="submit">Add Payment</button>
    </form>
    <br>
    <a href="payment_details.php">
        <button>Back to Payment Details</button>
    </a>
</body>
</html>
