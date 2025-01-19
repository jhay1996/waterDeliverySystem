<?php 
// Include the database connection file
include('db_connect.php');

// Handle saving or updating a product
if (isset($_POST['save_product'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0; // Default qty to zero
    $category_id = (int)$_POST['id']; // Get the category_id (from the dropdown)
    $price = (float)$_POST['price'];

    // Use prepared statements to prevent SQL injection
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing record in product_list table
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE product_list SET qty=? WHERE id=?");
        $stmt->bind_param("ii", $qty, $id);
        $stmt->execute(); // Execute the update query
    }
}

// Fetch categories for the dropdown menu
$categories = $conn->query("SELECT id, name FROM category_list ORDER BY id ASC");
?>

<!-- Header for Product Inventory Section -->
<div class="container mt-4">
    <h3>Inventory for Products</h3>
</div>  

<!-- Search Bar -->
<div class="container mb-4">
    <input type="text" class="form-control" id="search" placeholder="Search Products...">
</div>

<!-- Product List Table -->
<div class="container mt-5">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="productTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all products
                $result = $conn->query("SELECT p.*, c.name AS category_name FROM product_list p LEFT JOIN category_list c ON p.category_id = c.id");
                if ($result && $result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        // Check if the quantity is below critical level (e.g., 5)
                        $criticalLevelAlert = $row['qty'] < 5 ? "alert-danger" : "alert-success";
                        $notificationText = $row['qty'] < 5 ? "Low Stock" : "Stock is Okay";
                        $badgeClass = $row['qty'] < 5 ? "badge-danger" : "badge-success"; // Red for low stock, Green for okay stock

                        echo "<tr class='$criticalLevelAlert'>
                                <td>{$i}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['category_name']}</td>
                                <td>{$row['price']}</td>
                                <td>{$row['qty']}</td>
                                <td><span class='badge $badgeClass'>{$notificationText}</span></td>
                                <td>
                                    <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['id']}' data-name='{$row['name']}' data-description='{$row['description']}' data-category='{$row['category_id']}' data-qty='{$row['qty']}' data-price='{$row['price']}'>
                                        <i class='fas fa-edit'></i> Re-stock
                                    </button>
                                </td>
                            </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Editing Product -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control form-control-lg" name="name" id="name" readonly>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control form-control-lg" name="description" id="description" readonly></textarea>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control form-control-lg" name="category_id" id="category_id" disabled>
                            <option value="">Select Category</option>
                            <?php
                            if ($categories && $categories->num_rows > 0) {
                                while ($row = $categories->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No categories available</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="qty">Quantity</label>
                        <input type="number" class="form-control form-control-lg" name="qty" id="qty" required>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control form-control-lg" name="price" id="price" step="0.01" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="product_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_product" class="btn btn-primary">Save Quantity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Modal, Pre-filling Data for Edit, and Search -->
<script>
    // On clicking Edit button, pre-fill modal with data
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('product_id').value = this.getAttribute('data-id');
            document.getElementById('name').value = this.getAttribute('data-name');
            document.getElementById('description').value = this.getAttribute('data-description');
            document.getElementById('category_id').value = this.getAttribute('data-category');
            document.getElementById('qty').value = this.getAttribute('data-qty');
            document.getElementById('price').value = this.getAttribute('data-price');
            $('#productModal').modal('show');
        });
    });

    // Search functionality
    document.getElementById('search').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#productTable tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const category = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const price = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const qty = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            if (name.includes(query) || description.includes(query) || category.includes(query) || price.includes(query) || qty.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<!-- Include necessary Bootstrap JS files for modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<!-- Custom Style -->
<style>
    body {
        background-color: white;
    }

    .alert-danger {
        background-color: #f8d7da;
    }

    .alert-success {
        background-color: #d4edda;
    }
</style>
