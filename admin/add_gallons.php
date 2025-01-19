<?php
// Include the database connection file
include('db_connect.php');

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle saving or updating a gallon item
if (isset($_POST['save_gallon'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0; // Default qty to zero
    $category_id = (int)$_POST['category_id']; // Get the category_id (from the dropdown)
    $price = (float)$_POST['price'];

    // Use prepared statements to prevent SQL injection
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing record
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE gallons SET name=?, description=?, qty=?, category_id=?, price=? WHERE gallon_id=?");
        $stmt->bind_param("ssdiid", $name, $description, $qty, $category_id, $price, $id);
        $result = $stmt->execute();
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO gallons (name, description, qty, category_id, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdid", $name, $description, $qty, $category_id, $price);
        $result = $stmt->execute();
    }

    if ($result) {
        echo "<div class='alert alert-success' id='successMessage'>Gallon saved successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// Fetch categories for the dropdown menu
$categories = $conn->query("SELECT id, name FROM category_list ORDER BY id ASC");
?>

<!-- Add Gallon Button -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gallons List</h3>
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#gallonModal">
            <i class="fas fa-plus-circle"></i> Add New Gallon
        </button>
    
    </div>
</div>

<!-- Search Bar -->
<div class="container mb-4">
    <input type="text" class="form-control" id="search" placeholder="Search Gallons...">
</div>

<!-- Modal for Adding/Editing Gallon -->
<div class="modal fade" id="gallonModal" tabindex="-1" role="dialog" aria-labelledby="gallonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="gallonModalLabel">Add/Edit Gallon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <!-- Gallon Name -->
                    <div class="form-group">
                        <label for="name">Gallons Name</label>
                        <input type="text" class="form-control form-control-lg" name="name" id="name" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control form-control-lg" name="description" id="description" required></textarea>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control form-control-lg" name="category_id" id="category_id" required>
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

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control form-control-lg" name="price" id="price" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="gallon_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_gallon" class="btn btn-primary">Save Gallon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Gallons List Table -->
<div class="container mt-5">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="gallonTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all gallons
                $result = $conn->query("SELECT g.*, c.name AS category_name FROM gallons g LEFT JOIN category_list c ON g.category_id = c.id");
                if ($result && $result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['category_name']}</td>
                                <td>{$row['price']}</td>
                                <td>
                                    <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['gallon_id']}' data-name='{$row['name']}' data-description='{$row['description']}' data-category='{$row['category_id']}' data-price='{$row['price']}'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                    <a href='delete.php?id={$row['gallon_id']}' class='btn btn-danger btn-sm'>
                                        <i class='fas fa-trash-alt'></i> Delete
                                    </a>
                                </td>
                            </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No gallons found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript for Modal, Pre-filling Data for Edit, and Search -->
<script>
    // On clicking Edit button, pre-fill modal with data
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('gallon_id').value = this.getAttribute('data-id');
            document.getElementById('name').value = this.getAttribute('data-name');
            document.getElementById('description').value = this.getAttribute('data-description');
            document.getElementById('category_id').value = this.getAttribute('data-category');
            document.getElementById('price').value = this.getAttribute('data-price');
            $('#gallonModal').modal('show');
        });
    });

    // Automatically hide success message after 2 seconds
    setTimeout(function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 1000);

    // Search functionality
    document.getElementById('search').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#gallonTable tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const category = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const price = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            if (name.includes(query) || description.includes(query) || category.includes(query) || price.includes(query)) {
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

<!-- Custom Style to Make the Background White -->
<style>
    body {
        background-color: white;
    }
</style>
