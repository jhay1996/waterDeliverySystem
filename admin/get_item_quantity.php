<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    
    // Prepare the query to prevent SQL injection
    $query = $conn->prepare("SELECT qty FROM rental_items WHERE item_id = ?");
    $query->bind_param('i', $item_id); // 'i' indicates the type is an integer
    $query->execute();
    
    // Fetch the result
    $result = $query->get_result();
    $item = $result->fetch_assoc();
    
    // Output the available quantity
    if ($item) {
        echo $item['qty'];
    } else {
        echo 0; // No such item found
    }

    $query->close();
}
?>
