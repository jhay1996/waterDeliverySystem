<?php
include('db_connect.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" method="POST" id="manage-items">
                    <div class="card">
                        <div class="card-header">
                            Item Management Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo isset($item['item_id']) ? $item['item_id'] : ''; ?>">
                            
                            <div class="form-group">
                                <label class="control-label">Item Name</label>
                                <input type="text" class="form-control" name="item_name" value="<?php echo isset($item['item_name']) ? $item['item_name'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea cols="30" rows="3" class="form-control" name="description"><?php echo isset($item['description']) ? $item['description'] : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Quantity</label>
                                <input type="number" class="form-control" name="qty" min="1" value="<?php echo isset($item['qty']) ? $item['qty'] : 1; ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Price</label>
                                <input type="number" class="form-control" name="price" min="0" value="<?php echo isset($item['price']) ? $item['price'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" name="save_item" class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                    <button type="button" class="btn btn-sm btn-default col-sm-3" onclick="$('#manage-items').get(0).reset()">Cancel</button>
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
                                        <th class="text-center">Details</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $items = $conn->query("SELECT * FROM rental_items ORDER BY item_id ASC");
                                    while($row = $items->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="text-center">
                                            <p>Name: <b><?php echo $row['item_name'] ?></b></p>
                                            <p>Description: <b><?php echo $row['description'] ?></b></p>
                                            <p>Quantity: <b><?php echo $row['qty'] ?></b></p>
                                            <p>Price:₱ <b><?php echo $row['price'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['item_id'] ?>">
                                                <button type="submit" name="edit_item" class="btn btn-sm btn-primary">Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['item_id'] ?>">
                                                <button type="submit" name="delete_item" class="btn btn-sm btn-danger">Delete</button>
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

<?php
// Save or Update Item
if (isset($_POST['save_item'])) {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];  // Get the price from the form

    // If updating, use the ID to update the existing record
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE rental_items SET item_name='$item_name', description='$description', qty='$qty', price='$price' WHERE item_id='$id'";
    } else {
        // Otherwise, insert a new record
        $query = "INSERT INTO rental_items (item_name, description, qty, price) 
                  VALUES ('$item_name', '$description', '$qty', '$price')";
    }

    $result = $conn->query($query);
    if ($result) {
        header("Location:index.php?page=add_rental_item");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Item
if (isset($_POST['delete_item'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM rental_items WHERE item_id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        header("Location:index.php?page=add_rental_item");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Edit Item
if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $query = "SELECT * FROM rental_items WHERE item_id = '$id'";
    $result = $conn->query($query);
    $item = $result->fetch_assoc();
    if ($item) {
        // Prepopulate the form with item data (display the data in the form)
        echo "<script>
                document.querySelector('[name=id]').value = '$id';
                document.querySelector('[name=item_name]').value = '{$item['item_name']}';
                document.querySelector('[name=description]').value = '{$item['description']}';
                document.querySelector('[name=qty]').value = '{$item['qty']}';
                document.querySelector('[name=price]').value = '{$item['price']}';
              </script>";
    }
}
?>
