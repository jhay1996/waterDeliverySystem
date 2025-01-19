<?php
// Include the database connection file
include('db_connect.php');

// Check if the id is passed in the URL
if (isset($_GET['id'])) {
    $gallon_id = (int)$_GET['id']; // Get the gallon_id from the URL

    // Prepare the SQL query to delete the gallon
    $stmt = $conn->prepare("DELETE FROM gallons WHERE gallon_id = ?");
    $stmt->bind_param("i", $gallon_id);

    if ($stmt->execute()) {
        // Redirect to the page after successful deletion
        header('Location: index.php?page=add_gallons'); // Replace 'index.php' with your page
        exit();
    } else {
        echo "Error deleting gallon: " . $stmt->error;
    }
} else {
    echo "No gallon ID specified.";
}
?>
