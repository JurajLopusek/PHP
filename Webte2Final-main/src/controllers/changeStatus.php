<?php
require '../../../../configFinal.php';

// Check if both question_id and active values are present in the GET request
if (isset($_GET['question_id']) && isset($_GET['active'])) {

    $questionId = $_GET['question_id'];
    $current_active = $_GET['active']; // This is now directly the new active status

    // Prepare the SQL statement to update the active status
    $sql_update_active = "UPDATE Questions SET active = ? WHERE question_id = ?";
    $stmt_update_active = $conn->prepare($sql_update_active);
    $stmt_update_active->bind_param("ii", $current_active, $questionId);

    // Execute the statement and provide feedback based on the result
    if ($stmt_update_active->execute()) {
        // Success
        $response = ['success' => true, 'message' => "The active status of question ID $questionId has been successfully updated."];
    } else {
        // Failure
        $response = ['success' => false, 'message' => "Failed to update the active status: " . $stmt_update_active->error];
    }
    
    // Close the prepared statement
    $stmt_update_active->close();

    // Send a JSON response back to the client
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // Missing parameters, exit with an error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => "Invalid request parameters."]);
    exit;
}
?>
