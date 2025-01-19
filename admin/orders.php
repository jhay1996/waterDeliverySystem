<?php
// Include database connection
include 'db_connect.php';

// Handle order submission via PHP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $products = $_POST['product_id'];
    $quantities = $_POST['qty'];

    // Validate form inputs (server-side)
    if (empty($name) || empty($address) || empty($email) || empty($mobile) || empty($products) || empty($quantities)) {
        echo 'All fields are required.';
        exit;
    }

    // Insert into `new_orders` with prepared statement
    $stmt = $conn->prepare("INSERT INTO new_orders (Name, Address, Email, Mobile, Status, orderdate) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 'To be Delivered';
    $orderdate = date('Y-m-d H:i:s');
    $stmt->bind_param("ssssss", $name, $address, $email, $mobile, $status, $orderdate);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $total_amount = 0;

        // Loop through products and insert order items
        $stmt_item = $conn->prepare("INSERT INTO order_items (ordernum, product_id, quantity, amount) VALUES (?, ?, ?, ?)");
        foreach ($products as $index => $product_id) {
            $quantity = $quantities[$index];
            // Fetch price using prepared statement
            $stmt_price = $conn->prepare("SELECT price FROM product_list WHERE id = ?");
            $stmt_price->bind_param("i", $product_id);
            $stmt_price->execute();
            $result = $stmt_price->get_result();
            if ($product = $result->fetch_assoc()) {
                $price = $product['price'];
                $amount = $price * $quantity;
                $total_amount += $amount;

                // Insert item into order_items table
                $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $amount);
                $stmt_item->execute();
            } else {
                // If the product doesn't exist, return an error
                echo 'Invalid product selected.';
                exit;
            }
        }

        // Update total amount in `new_orders` using prepared statement
        $stmt_update = $conn->prepare("UPDATE new_orders SET Amount = ? WHERE ordernum = ?");
        $stmt_update->bind_param("di", $total_amount, $order_id);
        $stmt_update->execute();

        header('Location: index.php?page=orders');
    } else {
        echo 'Failed to place order.';
    }
    exit;
}

// Fetch orders and their corresponding total amount from order_items
$orders = $conn->query("SELECT o.*, SUM(oi.amount) AS total_amount 
                        FROM new_orders o
                        LEFT JOIN order_items oi ON o.ordernum = oi.ordernum
                        GROUP BY o.ordernum");

// Fetch products for the form
$products = $conn->query("SELECT * FROM product_list");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 20px;
        }
        .btn-info, .btn-success, .btn-primary, .btn-secondary {
            transition: background-color 0.3s ease;
        }
        .btn-info:hover, .btn-success:hover, .btn-primary:hover, .btn-secondary:hover {
            background-color: #0056b3;
        }
        .table {
            background-color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-delivered {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: red;
            font-weight: bold;
        }
        .modal-header {
            background-color: #343a40;
            color: white;
        }
        .modal-body {
            background-color: #e9ecef;
        }
        .form-control, .btn {
            border-radius: 0.25rem;
        }
        #totalAmount {
            font-weight: bold;
            font-size: 1.2rem;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Orders</h4>
            <button class="btn btn-success" data-toggle="modal" data-target="#addOrderModal">
                <i class="fas fa-plus"></i> Add Order
            </button>
        </div>
        <div class="card-body">
            <!-- Search input field -->
            <input type="text" id="searchOrder" class="form-control" placeholder="Search orders...">
            <br><br>

            <table class="table table-bordered" id="ordersTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['Name'] ?></td>
                            <td><?= $row['Address'] ?></td>
                            <td><?= $row['Email'] ?></td>
                            <td><?= $row['Mobile'] ?></td>
                            <td class="<?= ($row['Status'] == 'Delivered') ? 'status-delivered' : 'status-pending' ?>"><?= $row['Status'] ?></td>
                            <td>₱<?= number_format($row['total_amount'], 2) ?></td>
                            <td><?= $row['orderdate'] ?></td>
                            <td>
                                <!-- View Button with Icon -->
                                <button class="btn btn-info btn-sm viewOrder" data-id="<?= $row['ordernum'] ?>">
                                    <i class="fas fa-eye"></i> <!-- Eye icon for viewing -->
                                </button>

                                <!-- Delivered Button with Icon -->
                                <button class="btn btn-success btn-sm markDelivered" data-id="<?= $row['ordernum'] ?>">
                                    <i class="fas fa-check-circle"></i> <!-- Check-circle icon for delivered -->
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" id="addOrderForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" class="form-control" name="mobile" required>
                    </div>
                    <hr>
                    <div id="orderItems">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Product</label>
                                <select class="form-control" name="product_id[]" required>
                                    <?php while ($product = $products->fetch_assoc()): ?>
                                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                                            <?= $product['name'] ?> - ₱<?= $product['price'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                            </div>
                            <div class="col-md-3">
                                <label>Price</label>
                                <input type="text" class="form-control itemPrice" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addItem" class="btn btn-secondary btn-sm">Add Item</button>
                    <hr>
                    <div class="form-group">
                        <label>Total Amount</label>
                        <input type="text" id="totalAmount" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="orderDetails">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <!-- Save Order Button -->
              
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Search functionality
        $('#searchOrder').on('keyup', function () {
            var searchTerm = $(this).val().toLowerCase();
            $('#ordersTable tbody tr').each(function () {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchTerm) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Add new item row in modal
        $('#addItem').click(function () {
            var newItemRow = $('#orderItems .form-group:first').clone();
            $('#orderItems').append(newItemRow);
        });

        // Update total amount
        $('#addOrderForm').on('change', '.qty, select[name="product_id[]"]', function () {
            var totalAmount = 0;
            $('#orderItems .form-group').each(function () {
                var price = parseFloat($(this).find('select option:selected').data('price'));
                var qty = parseInt($(this).find('.qty').val());
                var amount = price * qty;
                totalAmount += amount;
                $(this).find('.itemPrice').val(amount.toFixed(2));
            });
            $('#totalAmount').val('₱' + totalAmount.toFixed(2));
        });

        // View order details
        $(document).on('click', '.viewOrder', function () {
            var orderId = $(this).data('id');
            $.ajax({
                url: 'view_order.php',
                method: 'GET',
                data: { ordernum: orderId },
                success: function (data) {
                    $('#orderDetails').html(data);
                    $('#viewOrderModal').modal('show');
                }
            });
        });

        // Mark order as delivered
        $(document).on('click', '.markDelivered', function () {
            var orderId = $(this).data('id');
            $.ajax({
                url: 'update_order_status.php',
                method: 'POST',
                data: { ordernum: orderId, status: 'Delivered' },
                success: function (data) {
                    location.reload();
                }
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>
