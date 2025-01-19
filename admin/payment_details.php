<?php
// Include database connection
include('db_connect.php');

// Fetch data from the database
$sql = "SELECT id, ordernum, name, address, mobile, total_amount, order_items, image_data FROM payment_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <h1>Payment Details</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order Number</th>
                <th>Name</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Total Amount</th>
                <th>Order Items</th>
                <th>Image Data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["ordernum"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["address"] . "</td>
                            <td>" . $row["mobile"] . "</td>
                            <td>" . $row["total_amount"] . "</td>
                            <td>" . $row["order_items"] . "</td>
                            <td>" . $row["image_data"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="add_payment.php">
        <button>Add Payment</button>
    </a>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
