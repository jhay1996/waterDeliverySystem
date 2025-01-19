<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $gallon_id = $_GET['id'];
    $query = $conn->query("SELECT qty FROM gallons WHERE gallon_id = '$gallon_id'");
    $gallon = $query->fetch_assoc();
    echo $gallon['qty'];
}
?>
