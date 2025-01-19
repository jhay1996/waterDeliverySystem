<?php
// Include the database connection file
include('db_connect.php');

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch inventory report
$report = $conn->query("SELECT r.*, g.name AS gallon_name FROM inventory_reports r LEFT JOIN gallons g ON r.gallon_id = g.gallon_id ORDER BY r.date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .text-center {
            text-align: center;
        }

        .no-records {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h4>Inventory Report</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gallon Name</th>
                    <th>Action</th>
                    <th>Quantity Changed</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($report && $report->num_rows > 0) {
                    $i = 1;
                    while ($row = $report->fetch_assoc()) {
                        echo "<tr>
                                <td class='text-center'>{$i}</td>
                                <td>{$row['gallon_name']}</td>
                                <td class='text-capitalize'>{$row['action']}</td>
                                <td class='text-center'>{$row['qty_changed']}</td>
                                <td class='text-center'>{$row['date']}</td>
                            </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center no-records'>No inventory actions recorded.</td></tr>";
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
