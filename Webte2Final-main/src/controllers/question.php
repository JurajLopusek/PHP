<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../../../configFinal.php'; 

// Read the JSON POST body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input data
if (!isset($data['question_text']) || !isset($data['question_type']) || !isset($data['subject'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

//make random 5 varchar code
$question_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
while (true) {
    $stmt = $conn->prepare("SELECT * FROM Questions WHERE question_code = ?");
    $stmt->bind_param("s", $question_code);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        break;
    }
    $question_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
}

if($_SESSION['role'] === 'admin'){
    $stmt = $conn->prepare("INSERT INTO Questions (user_id, question_text, question_type, subject, options_count, active, question_code, created_at, updated_at) VALUES (?,?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $options_count = ($data['question_type'] == 'multiple_choice') ? $data['answer_type'] : NULL; 
    $active = 1;
    $stmt->bind_param("issssis", $data['user'], $data['question_text'], $data['question_type'],$data['subject'], $options_count, $active, $question_code);
}
else{
    $stmt = $conn->prepare("INSERT INTO Questions (user_id, question_text, question_type, subject, options_count, active, question_code, created_at, updated_at) VALUES (?,?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $options_count = ($data['question_type'] == 'multiple_choice') ? $data['answer_type'] : NULL; 
    $active = 1;
    $stmt->bind_param("issssis", $_SESSION['user_id'], $data['question_text'], $data['question_type'],$data['subject'], $options_count, $active, $question_code);

}
// Insert query

// Execute and check for errors
if (!$stmt->execute()) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

$question_id = $stmt->insert_id;
$stmt->close();

// Handle options if the question type is multiple_choice
if ($data['question_type'] == 'multiple_choice') {
    foreach ($data['options'] as $option) {
        $stmt = $conn->prepare("INSERT INTO QuestionOptions (question_id, option_text) VALUES (?, ?)");
        $stmt->bind_param("is", $question_id, $option);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

// Success response
http_response_code(200);
echo json_encode(['success' => 'Question submitted successfully']);
?>
