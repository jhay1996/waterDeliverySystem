<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    
    // Fetch the price from rental_items table
    $query = $conn->prepare("SELECT price FROM rental_items WHERE item_id = ?");
    $query->bind_param('i', $item_id); // 'i' indicates the type is integer
    $query->execute();
    $result = $query->get_result();
    $item = $result->fetch_assoc();
    
    // Output the price
    if ($item) {
        echo $item['price'];
    } else {
        echo 0; // No such item found
    }

    $query->close();
}
?>
