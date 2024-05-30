<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../configFinal.php';  // Ensure this path is correct

// This script should receive JSON data
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'] ?? null; // Allow null if not logged in
$question_id = $input['question_id'] ?? null;
$answer = $input['answer'] ?? null;

if (!$question_id || !$answer) {
    // Output error if the necessary data is missing
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Missing question ID or answer']);
    exit;
}

// First, get the type of the question from the database
if ($questionStmt = $conn->prepare("SELECT question_type FROM Questions WHERE question_id = ?")) {
    $questionStmt->bind_param("i", $question_id);
    $questionStmt->execute();
    $questionResult = $questionStmt->get_result();
    $questionData = $questionResult->fetch_assoc();
    $questionStmt->close();

    if (!$questionData) {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Question not found']);
        exit;
    }

    $question_type = $questionData['question_type'];

    // Prepare to insert multiple answers if necessary
    if ($question_type == 'multiple_choice') {
        if (is_array($answer)) {
            foreach ($answer as $option_id) {
                if (!$insertStmt = $conn->prepare("INSERT INTO Responses (question_id, user_id, option_id, created_at) VALUES (?, ?, ?, NOW())")) {
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(['error' => $conn->error]);
                    exit;
                }
                $insertStmt->bind_param("iii", $question_id, $user_id, $option_id);
                $insertStmt->execute();
                $insertStmt->close();
            }
            echo json_encode(['success' => 'Responses submitted successfully']);
        } else {
            // Handle a single answer for multiple choice
            $option_id = $answer;
            $response_text = '';
            insertResponse($conn, $question_id, $user_id, $option_id, $response_text);
        }
    } else if ($question_type == 'open_ended') {
        $response_text = $answer;
        $option_id = null;
        insertResponse($conn, $question_id, $user_id, $option_id, $response_text);
    }
} else {
    // Output SQL error when querying question type
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $conn->error]);
}

$conn->close();

// Function to handle insertion of a single response
function insertResponse($conn, $question_id, $user_id, $option_id, $response_text) {
    if ($insertStmt = $conn->prepare("INSERT INTO Responses (question_id, user_id, option_id, response_text, created_at) VALUES (?, ?, ?, ?, NOW())")) {
        $insertStmt->bind_param("iiis", $question_id, $user_id, $option_id, $response_text);
        if ($insertStmt->execute()) {
            echo json_encode(['success' => 'Response submitted successfully']);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to submit response']);
        }
        $insertStmt->close();
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $conn->error]);
    }
}
?>
