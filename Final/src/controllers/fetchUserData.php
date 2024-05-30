<?php
// fetchUserData.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch user data
    $userData = $result->fetch_assoc();
    
    // Return user data as JSON
    header('Content-Type: application/json');
    echo json_encode($userData);
} else {
    echo "Username parameter is missing";
}
?>
