<?php
// Include the database connection file
include('db_connect.php');

// Handle search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    // If search term is provided, filter the results based on the search term
    $sql = "SELECT g.*, c.name AS category_name 
            FROM gallons g 
            LEFT JOIN category_list c ON g.category_id = c.id
            WHERE g.name LIKE ? OR g.description LIKE ? OR c.name LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If search term is empty, fetch all records
    $sql = "SELECT g.*, c.name AS category_name 
            FROM gallons g 
            LEFT JOIN category_list c ON g.category_id = c.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}

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
