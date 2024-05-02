<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Include your existing database configuration
require '../../../../configFinal.php';

$data = json_decode(file_get_contents('php://input'), true);

$currentPassword = $data['current_password'];
$newPassword = $data['new_password'];
$confirmPassword = $data['confirm_password2'];

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'error' => 'Passwords do not match.']);
    exit;
}

// Function to validate the current password
function validateCurrentPassword($userId, $currentPassword, $conn) {
    $hashedPassword = null; // Initialize the variable
    $sql = "SELECT password FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        if (password_verify($currentPassword, $hashedPassword)) {
            return true;
        }
    }
    return false;
}

// Function to update the user password
function updateUserPassword($userId, $newPassword, $conn) {
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE Users SET password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedNewPassword, $userId);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Validate current password
if (!validateCurrentPassword($_SESSION['user_id'], $currentPassword, $conn)) {
    echo json_encode(['success' => false, 'error' => 'Current password is incorrect.']);
    exit;
}

// Update the password if validation passes
if (updateUserPassword($_SESSION['user_id'], $newPassword, $conn)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update password.']);
    exit;
}
?>
