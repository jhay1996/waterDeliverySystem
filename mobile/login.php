<?php
// Database connection
$servername = "localhost";
$username = "u807574647_avawaters"; // Your DB username
$password = "Avawaters123"; // Your DB password
$dbname = "u807574647_avawaters"; // Your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the POST data
$action = $_POST['action'] ?? '';  // Getting the action value
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Debug: Print the action parameter to check if it's being passed correctly
error_log("Action: " . $action);

// Handling login
if ($action === 'login') {
    $stmt = $conn->prepare("SELECT * FROM riders WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Compare the entered password with the stored plain text password
        if ($password === $user['password']) {
            // Password matches
            echo json_encode(['status' => 'success']);
        } else {
            // Password doesn't match
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        // User not found
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

$conn->close();
?>
