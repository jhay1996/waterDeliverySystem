<?php
// Include database connection
include 'admin/db_connect.php';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $status = $_POST['status'];
    $products = $_POST['product_id']; // Selected products
    $quantities = $_POST['qty']; // Corresponding quantities
    $total_amount = $_POST['total_amount']; // Total order amount
    $orderdate = date("Y-m-d H:i:s");

    // Insert order into `new_orders`
    $stmt = $conn->prepare("INSERT INTO new_orders (Name, Address, Email, Mobile, Status, Amount, orderdate) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $address, $email, $mobile, $status, $total_amount, $orderdate);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert products into `order_items`
    $stmt_item = $conn->prepare("INSERT INTO order_items (ordernum, product_id, quantity, amount) VALUES (?, ?, ?, ?)");
    foreach ($products as $index => $product_id) {
        $quantity = $quantities[$index];
        // Fetch product price
        $stmt_price = $conn->prepare("SELECT price FROM product_list WHERE id = ?");
        $stmt_price->bind_param("i", $product_id);
        $stmt_price->execute();
        $result = $stmt_price->get_result();
        $product = $result->fetch_assoc();
        $price = $product['price'];
        $amount = $price * $quantity;
        
        // Insert order item into `order_items`
        $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $amount);
        $stmt_item->execute();
    }
    $stmt_item->close();
    header('Location: index.php'); // Redirect after adding order
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Button to trigger modal -->
    <div class="container mt-5">
        <button class="btn btn-success" data-toggle="modal" data-target="#addOrderModal">
            <i class="fas fa-plus"></i> Add Order
        </button>
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOrderModalLabel">Add New Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                        </div>
                        <hr>
                        <div id="orderItems">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="product">Product</label>
                                    <select class="form-control" name="product_id[]" required>
                                        <?php
                                        // Fetch products from the database
                                        $products = $conn->query("SELECT * FROM product_list");
                                        while ($product = $products->fetch_assoc()) {
                                            echo "<option value='{$product['id']}' data-price='{$product['price']}'>";
                                            echo "{$product['name']} - ₱{$product['price']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="qty">Quantity</label>
                                    <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control itemPrice" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount</label>
                            <input type="text" id="totalAmount" class="form-control" name="total_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label for="status">Order Status</label>
                            <select class="form-control" name="status">
                                <option value="Pending">Pending</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Calculate total amount dynamically when quantity or product is changed
            $(document).on('change', '.qty, select[name="product_id[]"]', function () {
                let total = 0;
                $('#orderItems .form-group.row').each(function () {
                    const qty = $(this).find('.qty').val();
                    const price = $(this).find('select option:selected').data('price');
                    const itemPrice = qty * price;
                    $(this).find('.itemPrice').val('₱' + itemPrice.toFixed(2));
                    total += itemPrice;
                });
                $('#totalAmount').val('₱' + total.toFixed(2));
            });
        });
    </script>
</body>
</html>
