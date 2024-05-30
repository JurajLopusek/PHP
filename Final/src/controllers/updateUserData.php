<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../configFinal.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form submission is for updating user data or password
    
    if (isset($_POST['user_id']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role'])) {
        // Update user data
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Prepare and execute the SQL statement to update user data
        $sql = "UPDATE Users SET username=?, email=?, role=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);
        if ($stmt->execute()) {
            // Success message
            echo json_encode(array("success" => true));
        } else {
            // Error message
            echo json_encode(array("success" => false, "error" => "Error updating user data"));
        }
        $stmt->close();
    } elseif (isset($_POST['user_id']) && isset($_POST['new_password_Admin'])) {
        // Update user password
        $user_id = $_POST['user_id'];
        $new_password = $_POST['new_password_Admin'];

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement to update user password
        $sql = "UPDATE Users SET password=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        if ($stmt->execute()) {
            // Success message
            echo json_encode(array("success" => true));
        } else {
            // Error message
            echo json_encode(array("success" => false, "error" => "Error updating password"));
        }
        $stmt->close();
    } else {
        // Invalid form submission
        echo json_encode(array("success" => false, "error" => "Invalid form data"));
    }
} else {
    // No form data submitted
    echo json_encode(array("success" => false, "error" => "No form data submitted"));
}

?>
