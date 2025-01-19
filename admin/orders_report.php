<?php
// Include your database connection file
include('db_connect.php');

// Query to get order data
$order_query = "SELECT * FROM new_orders";

// Execute the query
$order_result = $conn->query($order_query);

// Check if there are errors in the query execution
if (!$order_result) {
    die("Query failed: " . $conn->error);
}

// Fetch data into an array
$orders = [];
if ($order_result->num_rows > 0) {
    while ($row = $order_result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order and Order List Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f5;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2, h4 {
            color: #333;
            text-align: center;
        }
        .print-btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        .table {
            border-collapse: separate !important;
            border-spacing: 0 10px !important;
        }
        .table thead th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
        }
        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Print Button -->
        <button onclick="window.print()" class="btn print-btn">Print Report</button>

        <h2>Order and Order List Report</h2>
        <h4>Orders and Product Details</h4>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="no-data">No orders found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['ordernum']) ?></td>
                            <td><?= htmlspecialchars($order['Name']) ?></td>
                            <td><?= htmlspecialchars($order['Address']) ?></td>
                            <td><?= htmlspecialchars($order['Mobile']) ?></td>
                            <td><?= htmlspecialchars($order['Email']) ?></td>
                            <td><?= htmlspecialchars($order['Status']) ?></td>
                            <td><?= htmlspecialchars($order['orderdate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
