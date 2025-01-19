<?php
include('db_connect.php');

// Fetch borrowed data for editing
if (isset($_POST['edit_borrowed'])) {
    $id = $_POST['id'];
    $query = "SELECT b.*, g.name as gallon_name, g.qty as available_qty FROM borrowed b LEFT JOIN gallons g ON b.gallon_id = g.gallon_id WHERE b.borrow_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $borrowed = $result->fetch_assoc();
}

?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" method="POST" id="manage-borrowed">
                    <div class="card">
                        <div class="card-header">Borrowed Form</div>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo isset($borrowed['borrow_id']) ? $borrowed['borrow_id'] : ''; ?>">
                            
                            <div class="form-group">
                                <label class="control-label">Select Gallon</label>
                                <select name="gallon_name" class="form-control" id="gallon_name" required>
                                    <option value="">Select Gallon</option>
                                    <?php
                                    $gallons = $conn->query("SELECT * FROM gallons ORDER BY name ASC");
                                    while ($gallon = $gallons->fetch_assoc()) {
                                        $selected = isset($borrowed['gallon_id']) && $borrowed['gallon_id'] == $gallon['gallon_id'] ? 'selected' : '';
                                        echo "<option value='{$gallon['gallon_id']}' $selected>{$gallon['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Available Quantity</label>
                                <input type="number" class="form-control" id="available_qty" value="<?php echo isset($borrowed['available_qty']) ? $borrowed['available_qty'] : 0; ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Customer Name</label>
                                <input type="text" class="form-control" name="customername" value="<?php echo isset($borrowed['customername']) ? $borrowed['customername'] : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Quantity</label>
                                <input type="number" class="form-control" name="qty" min="1" value="<?php echo isset($borrowed['qty']) ? $borrowed['qty'] : 1; ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Date Borrowed</label>
                                <input type="date" class="form-control" name="date_borrowed" value="<?php echo isset($borrowed['date_borrowed']) ? date('Y-m-d', strtotime($borrowed['date_borrowed'])) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="borrowed" <?php echo (isset($borrowed['status']) && $borrowed['status'] == 'borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                                    <option value="returned" <?php echo (isset($borrowed['status']) && $borrowed['status'] == 'returned') ? 'selected' : ''; ?>>Returned</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" name="save_borrowed" class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                    <button type="button" class="btn btn-sm btn-default col-sm-3" onclick="$('#manage-borrowed').get(0).reset()">Cancel</button>
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
                                        <th class="text-center">Gallon Name</th>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Date Borrowed</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $borrowed = $conn->query("SELECT b.*, g.name as gallon_name FROM borrowed b LEFT JOIN gallons g ON b.gallon_id = g.gallon_id ORDER BY b.borrow_id ASC");
                                    while ($row = $borrowed->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo $row['gallon_name']; ?></td>
                                        <td class="text-center"><?php echo $row['customername']; ?></td>
                                        <td class="text-center"><?php echo $row['qty']; ?></td>
                                        <td class="text-center"><?php echo date('m/d/y', strtotime($row['date_borrowed'])); ?></td>

                                        <td class="text-center"><?php echo $row['status']; ?></td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['borrow_id']; ?>">
                                                <button type="submit" name="edit_borrowed" class="btn btn-sm btn-primary">Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['borrow_id']; ?>">
                                                <button type="submit" name="delete_borrowed" class="btn btn-sm btn-danger">Delete</button>
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

<!-- Fetch available quantity dynamically -->
<script>
document.getElementById('gallon_name').addEventListener('change', function() {
    var gallon_id = this.value;

    if (gallon_id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_gallon_quantity.php?id=' + gallon_id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('available_qty').value = xhr.responseText;
            } else {
                alert('Error fetching quantity');
            }
        };
        xhr.send();
    } else {
        document.getElementById('available_qty').value = 0;
    }
});
</script>

<?php
// Save or update borrowed data
if (isset($_POST['save_borrowed'])) {
    $gallon_name = $_POST['gallon_name'];
    $customername = $_POST['customername'];
    $qty = $_POST['qty'];
    $date_borrowed = $_POST['date_borrowed'];
    $status = $_POST['status'];

    // Fetch available quantity
    $query = $conn->prepare("SELECT qty FROM gallons WHERE gallon_id = ?");
    $query->bind_param('i', $gallon_name);
    $query->execute();
    $result = $query->get_result();
    $gallon = $result->fetch_assoc();
    $available_qty = $gallon['qty'];

    // Validate quantity

    
    if ($status === 'borrowed' && $qty > $available_qty) {
        echo "<script>alert('Insufficient quantity available');</script>";
    } else {
        // Update quantity based on status
        $new_qty = ($status === 'borrowed') ? $available_qty - $qty : $available_qty + $qty;

        // Update borrowed record
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            $query = "UPDATE borrowed SET gallon_id = ?, customername = ?, qty = ?, date_borrowed = ?, status = ? WHERE borrow_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('issisi', $gallon_name, $customername, $qty, $date_borrowed, $status, $id);
            $stmt->execute();
        } else {
            $query = "INSERT INTO borrowed (gallon_id, customername, qty, date_borrowed, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('issis', $gallon_name, $customername, $qty, $date_borrowed, $status);
            $stmt->execute();
        }

        // Update gallon quantity
        $query = "UPDATE gallons SET qty = ? WHERE gallon_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $new_qty, $gallon_name);
        $stmt->execute();

        // Redirect to index.php after saving or updating
        header('Location: index.php?page=borrow_gallons');
        exit();
    }
}

// Delete borrowed record
if (isset($_POST['delete_borrowed'])) {
    $id = $_POST['id'];

    // Fetch borrow details
    $query = "SELECT * FROM borrowed WHERE borrow_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $borrowed = $result->fetch_assoc();

    // Update gallon quantity
    $query = "UPDATE gallons SET qty = qty + ? WHERE gallon_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $borrowed['qty'], $borrowed['gallon_id']);
    $stmt->execute();

    // Delete borrow record
    $query = "DELETE FROM borrowed WHERE borrow_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Redirect to index.php after deletion
    header('Location:index.php?page=borrow_gallons');
    exit();
}
?>
