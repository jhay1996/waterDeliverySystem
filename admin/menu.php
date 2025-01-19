<?php
include('db_connect.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" method="POST" enctype="multipart/form-data" id="manage-menu">
                    <div class="card">
                        <div class="card-header">
                            Menu Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo isset($menu_item['id']) ? $menu_item['id'] : ''; ?>">
                            <div class="form-group">
                                <label class="control-label">Product Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo isset($menu_item['name']) ? $menu_item['name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Product Description</label>
                                <textarea cols="30" rows="3" class="form-control" name="description"><?php echo isset($menu_item['description']) ? $menu_item['description'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="availability" <?php echo isset($menu_item['status']) && $menu_item['status'] == 1 ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="availability">Available</label>
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="control-label">Category</label>
                                <select name="category_id" class="custom-select browser-default">
                                    <?php
                                    $cat = $conn->query("SELECT * FROM category_list order by name asc ");
                                    while ($row = $cat->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($menu_item['category_id']) && $menu_item['category_id'] == $row['id'] ? 'selected' : ''; ?>>
                                        <?php echo $row['name'] ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Price</label>
                                <input type="number" class="form-control text-right" name="price" step="any" value="<?php echo isset($menu_item['price']) ? $menu_item['price'] : ''; ?>">
                            </div>
                            <!-- Added Quantity Field -->
                            <div class="form-group">
                                <label class="control-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" min="1" value="<?php echo isset($menu_item['qty']) ? $menu_item['qty'] : 1; ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Image</label>
                                <input type="file" class="form-control" name="img" onchange="previewImage(event)">
                            </div>
                            <div class="form-group">
                                <img src="" alt="" id="cimg" style="max-height: 10vh; max-width: 6vw;">
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" name="save_menu" class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                    <button type="button" class="btn btn-sm btn-default col-sm-3" onclick="$('#manage-menu').get(0).reset()">Cancel</button>
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
                                        <th class="text-center">Img</th>
                                        <th class="text-center">Details</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $cats = $conn->query("SELECT p.*, c.name as cat FROM product_list p inner join category_list c on c.id = p.category_id order by p.id asc");
                                    while($row = $cats->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>

                                        <td class="text-center">
                                            <img src="<?php echo isset($row['img_path']) ? '../assets/img/'.$row['img_path'] :'' ?>" alt="" id="cimg">
                                        </td>
                                        <td class="text-center">
                                            <p>Name : <b><?php echo $row['name'] ?></b></p>
                                            <p>Category : <b><?php echo $row['cat'] ?></b></p>
                                            <p>Description : <b class="truncate"><?php echo $row['description'] ?></b></p>
                                            <p>Price : <b><?php echo "₱".number_format($row['price'], 2) ?></b></p>
                                            <p>Quantity : <b><?php echo $row['qty'] ?></b></p> <!-- Display quantity -->
                                        </td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                <button type="submit" name="edit_menu" class="btn btn-sm btn-primary">Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                <button type="submit" name="delete_menu" class="btn btn-sm btn-danger">Delete</button>
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
    img#cimg,.cimg{
        max-height: 10vh;
        max-width: 6vw;
    }
    td{
        vertical-align: middle !important;
    }
    td p{
        margin: unset !important;
    }
    .custom-switch,.custom-control-input,.custom-control-label{
        cursor:pointer;
    }
    b.truncate {
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
         -webkit-line-clamp: 3; 
         -webkit-box-orient: vertical;    
         font-size: small;
         color: #000000cf;
         font-style: italic;
    }
</style>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.getElementById('cimg');
            img.src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

<?php
// Save or Update Menu Item
if (isset($_POST['save_menu'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category_id = $_POST['category_id'];
    $status = isset($_POST['status']) ? 1 : 0;

    // Process image upload if any
    $img_path = isset($_POST['img_path']) ? $_POST['img_path'] : "";
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img = $_FILES['img'];
        $img_path = time() . "_" . basename($img["name"]);
        move_uploaded_file($img["tmp_name"], "../assets/img/" . $img_path);
    }

    // If updating, use the ID to update the existing record
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE product_list SET name='$name', description='$description', price='$price', qty='$quantity', img_path='$img_path', category_id='$category_id', status='$status' WHERE id='$id'";
    } else {
        // Otherwise, insert a new record
        $query = "INSERT INTO product_list (name, description, price, qty, img_path, category_id, status) 
                  VALUES ('$name', '$description', '$price', '$quantity', '$img_path', '$category_id', '$status')";
    }

    $result = $conn->query($query);
    if ($result) {
        header("Location:index.php?page=menu");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Menu Item
if (isset($_POST['delete_menu'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM product_list WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        header("Location: index.php?page=menu");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Edit Menu Item
if (isset($_POST['edit_menu'])) {
    $id = $_POST['id'];
    $query = "SELECT * FROM product_list WHERE id = '$id'";
    $result = $conn->query($query);
    $menu_item = $result->fetch_assoc();
    if ($menu_item) {
        // Prepopulate the form with menu item data (display the data in the form)
        echo "<script>
                document.querySelector('[name=id]').value = '$id';
                document.querySelector('[name=name]').value = '{$menu_item['name']}';
                document.querySelector('[name=description]').value = '{$menu_item['description']}';
                document.querySelector('[name=price]').value = '{$menu_item['price']}';
                document.querySelector('[name=quantity]').value = '{$menu_item['qty']}';
                document.querySelector('[name=category_id]').value = '{$menu_item['category_id']}';
                document.querySelector('[name=status]').checked = {$menu_item['status']} == 1;

                // Display the current image if exists
                const imgPath = '../assets/img/{$menu_item['img_path']}';
                const imgElement = document.getElementById('cimg');
                if (imgElement) {
                    imgElement.src = imgPath;
                }
              </script>";
    }
}
?>  
