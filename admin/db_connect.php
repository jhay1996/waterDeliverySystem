<?php 

$conn = new mysqli('localhost', 'u807574647_avawaters', 'Avawaters123', 'u807574647_avawaters');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
