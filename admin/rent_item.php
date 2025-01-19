<?php
include('db_connect.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Fetch rented item data for editing
if (isset($_POST['edit_rented'])) {
    $id = $_POST['id'];
    $query = "SELECT r.*, i.item_name, i.qty as item_qty, i.price FROM rented r LEFT JOIN rental_items i ON r.item_id = i.item_id WHERE r.rent_id = '$id'";
    $result = $conn->query($query);
    $rented = $result->fetch_assoc();
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" method="POST" id="manage-rented">
                    <div class="card">
                        <div class="card-header">
                            Rented Item Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo isset($rented['rent_id']) ? $rented['rent_id'] : ''; ?>">

                            <div class="form-group">
                                <label class="control-label">Select Item</label>
                                <select name="item_name" class="form-control" id="item_name">
                                    <option value="">Select Item</option>
                                    <?php
                                    $items = $conn->query("SELECT * FROM rental_items ORDER BY item_name ASC");
                                    while ($item = $items->fetch_assoc()) {
                                        $selected = isset($rented['item_id']) && $rented['item_id'] == $item['item_id'] ? 'selected' : '';
                                        echo "<option value='{$item['item_id']}' $selected>{$item['item_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Available Quantity</label>
                                <input type="number" class="form-control" id="available_qty" value="<?php echo isset($rented['item_qty']) ? $rented['item_qty'] : 0; ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Customer Name</label>
                                <input type="text" class="form-control" name="customername" value="<?php echo isset($rented['customername']) ? $rented['customername'] : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Quantity</label>
                                <input type="number" class="form-control" name="qty" min="1" value="<?php echo isset($rented['qty']) ? $rented['qty'] : 1; ?>" id="qty">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Total Price</label>
                                <input type="number" class="form-control" id="total_price" value="<?php echo isset($rented['total_price']) ? $rented['total_price'] : 0; ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Date Rented</label>
                                <input type="date" class="form-control" name="date_rented" value="<?php echo isset($rented['date_rented']) ? $rented['date_rented'] : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="rented" <?php echo (isset($rented['status']) && $rented['status'] == 'rented') ? 'selected' : ''; ?>>Rented</option>
                                    <option value="returned" <?php echo (isset($rented['status']) && $rented['status'] == 'returned') ? 'selected' : ''; ?>>Returned</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" name="save_rented" class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                    <button type="button" class="btn btn-sm btn-default col-sm-3" onclick="$('#manage-rented').get(0).reset()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Date Rented</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $rented = $conn->query("SELECT r.*, i.item_name FROM rented r LEFT JOIN rental_items i ON r.item_id = i.item_id ORDER BY r.rent_id ASC");
                                    while($row = $rented->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="text-center"><?php echo $row['item_name'] ?></td>
                                        <td class="text-center"><?php echo $row['customername'] ?></td>
                                        <td class="text-center"><?php echo $row['qty'] ?></td>
                                        <td class="text-center"><?php echo $row['date_rented'] ?></td>
                                        <td class="text-center"><?php echo $row['status'] ?></td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['rent_id'] ?>">
                                                <button type="submit" name="edit_rented" class="btn btn-sm btn-primary">Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['rent_id'] ?>">
                                                <button type="submit" name="delete_rented" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>    
</div>

<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset !important;
    }
</style>

<script>
// Fetch available quantity and price when item is selected
document.getElementById('item_name').addEventListener('change', function() {
    var item_id = this.value;

    if (item_id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_item_quantity.php?id=' + item_id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('available_qty').value = xhr.responseText;
                fetchItemPrice(item_id);
            } else {
                alert('Error fetching quantity');
            }
        };
        xhr.send();
    } else {
        document.getElementById('available_qty').value = 0;
        document.getElementById('total_price').value = 0;
    }
});

document.getElementById('qty').addEventListener('input', function() {
    var qty = this.value;
    var price = document.getElementById('item_price') ? document.getElementById('item_price').value : 0;
    var total_price = qty * price;
    document.getElementById('total_price').value = total_price;
});

function fetchItemPrice(item_id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_item_price.php?id=' + item_id, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var price = xhr.responseText;
            document.getElementById('item_price').value = price;

            // Recalculate total price if the quantity is set
            var qty = document.getElementById('qty').value;
            var total_price = qty * price;
            document.getElementById('total_price').value = total_price;
        } else {
            alert('Error fetching item price');
        }
    };
    xhr.send();
}
</script>

<!-- Hidden Field for Item Price -->
<input type="hidden" id="item_price">

<?php
// Save or Update Rented Item
if (isset($_POST['save_rented'])) {
    $item_name = $_POST['item_name'];
    $customername = $_POST['customername'];
    $qty = $_POST['qty'];
    $date_rented = $_POST['date_rented'];
    $status = $_POST['status'];

    // Fetch item price for total price calculation
    $query = $conn->prepare("SELECT price, qty FROM rental_items WHERE item_id = ?"); 
    $query->bind_param('i', $item_name); 
    $query->execute(); 
    $result = $query->get_result(); 
    $item = $result->fetch_assoc(); 
    $price = $item['price'];
    $available_qty = $item['qty'];

    // Calculate total price
    $total_price = $price * $qty;

    // If the item is returned, add the quantity back to the inventory
    if ($status == 'returned') {
        $new_qty = $available_qty + $qty;  // Increase the available quantity by the returned quantity
    } else {
        $new_qty = $available_qty - $qty;  // Decrease available quantity by the rented quantity
    }

    // Update rental record
    if (isset($_POST['id']) && $_POST['id']) {
        // Update
        $id = $_POST['id'];
        $query = "UPDATE rented SET item_id = ?, customername = ?, qty = ?, total_price = ?, date_rented = ?, status = ? WHERE rent_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isisssi', $item_name, $customername, $qty, $total_price, $date_rented, $status, $id);
        $stmt->execute();

        // Update inventory quantity
        $query = "UPDATE rental_items SET qty = ? WHERE item_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $new_qty, $item_name);
        $stmt->execute();

    } else {
        // Insert new rental
        $query = "INSERT INTO rented (item_id, customername, qty, total_price, date_rented, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isisss', $item_name, $customername, $qty, $total_price, $date_rented, $status);
        $stmt->execute();

        // Update inventory quantity
        $query = "UPDATE rental_items SET qty = ? WHERE item_id = ?"; 
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $new_qty, $item_name);
        $stmt->execute();
    }

    // Redirect to the rent item page after saving or updating
    header("Location: index.php?page=rent_item");
    exit();
}

// Delete Rented Item
if (isset($_POST['delete_rented'])) {
    $id = $_POST['id'];

    // Delete rental record
    $query = "DELETE FROM rented WHERE rent_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Optionally, update inventory quantity if needed, e.g. increase available qty for the returned item
    $query = "SELECT item_id, qty FROM rented WHERE rent_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rented_item = $result->fetch_assoc();

    if ($rented_item) {
        // Get the item_id and qty from rented item
        $item_id = $rented_item['item_id'];
        $rented_qty = $rented_item['qty'];

        // Update the rental item quantity in inventory
        $query = "UPDATE rental_items SET qty = qty + ? WHERE item_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $rented_qty, $item_id);
        $stmt->execute();
    }

    // Redirect after delete
    header("Location: index.php?page=rent_item");
    exit();
}
?>
