<?php
// Include your database connection file
include('db_connect.php');

// Set up variables
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Query to get rented item data
$query = "SELECT r.rent_id, i.item_name, r.customername, r.qty, r.date_rented, r.status, r.total_price 
          FROM rented r
          JOIN rental_items i ON r.item_id = i.item_id
          WHERE r.date_rented BETWEEN '$start_date' AND '$end_date' ORDER BY r.date_rented DESC";

$result = $conn->query($query);

// Calculate total sales
$total_sales = 0;
$sales_by_item = [];

while ($row = $result->fetch_assoc()) {
    $total_sales += $row['total_price'];
    $item_name = $row['item_name'];

    // Aggregate sales by item
    if (!isset($sales_by_item[$item_name])) {
        $sales_by_item[$item_name] = 0;
    }
    $sales_by_item[$item_name] += $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @media print {
            /* Hide navbar and print button during printing */
            .navbar, .print-btn {
                display: none;
            }

            /* Additional styling to make the table look cleaner in print */
            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th, table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            body {
                font-size: 12px;
            }
        }
    </style>

    <script type="text/javascript">
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <!-- Print Button at the Top -->
        <button onclick="printReport()" class="btn btn-success print-btn mb-4">Print Report</button>

        <h2>Sales Report</h2>

        <!-- Sales Filter Form -->
        <form action="" method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>

        <!-- Displaying Start and End Date -->
        <h4>Start Date: <?php echo $start_date; ?></h4>
        <h4>End Date: <?php echo $end_date; ?></h4>

        <!-- Total Sales and Sales by Item -->
        <h4>Total Sales: <?php echo number_format($total_sales, 2); ?> </h4>

        <h4>Sales by Item</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display sales by item
                foreach ($sales_by_item as $item_name => $sales) {
                    echo "<tr>
                            <td>$item_name</td>
                            <td>" . number_format($sales, 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <h4>Sales List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rent ID</th>
                    <th>Item Name</th>
                    <th>Customer Name</th>
                    <th>Quantity</th>
                    <th>Date Rented</th>
                    <th>Status</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the result pointer
                $result->data_seek(0);

                // Display detailed sales data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['rent_id']}</td>
                            <td>{$row['item_name']}</td>
                            <td>{$row['customername']}</td>
                            <td>{$row['qty']}</td>
                            <td>{$row['date_rented']}</td>
                            <td>{$row['status']}</td>
                            <td>" . number_format($row['total_price'], 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
