<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';

$question_id = $_GET['qid'];

$sql = "SELECT question_type, question_text FROM Questions WHERE question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();

$responseData = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $question_type = $row["question_type"];
    $question_text = $row["question_text"];

    // Add question_text to response data
    $responseData["question_text"] = $question_text;
    $responseData["data"] = [];

    if ($question_type === 'multiple_choice') {
        $sql = "SELECT o.option_text FROM QuestionOptions o JOIN Responses r ON r.option_id = o.option_id WHERE r.question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $optionCounts = [];

        // Loop through the result set
        while ($row = $result->fetch_assoc()) {
            $option_text = $row['option_text'];

            // If option_text exists in the array, increment its count; otherwise, initialize it to 1
            if (isset($optionCounts[$option_text])) {
                $optionCounts[$option_text]++;
            } else {
                $optionCounts[$option_text] = 1;
            }
        }
        // Add optionCounts to response data
        $responseData["data"]["current"] = $optionCounts;


        $sql = "SELECT id, created_at FROM Backup WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $backup_id = $row['id'];
            $created_at = $row['created_at'];
            $sql = "SELECT o.option_text FROM QuestionOptions o JOIN BackupResponses r ON r.option_id = o.option_id WHERE r.backup_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $backup_id);
            $stmt->execute();
            $result1 = $stmt->get_result();
            $optionCounts = [];
            while ($row1 = $result1->fetch_assoc()) {
                $option_text = $row1['option_text'];
                // If option_text exists in the array, increment its count; otherwise, initialize it to 1
                if (isset($optionCounts[$option_text])) {
                    $optionCounts[$option_text]++;
                } else {
                    $optionCounts[$option_text] = 1;
                }
            }
            // Add optionCounts to response data
            $responseData["data"][$created_at] = $optionCounts;
        }

    } elseif ($question_type === 'open_ended') {
        $sql = "SELECT response_text FROM Responses WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $responseCounts = [];

        // Loop through the result set
        while ($row = $result->fetch_assoc()) {
            $response_text = $row['response_text'];

            // If response_text exists in the array, increment its count; otherwise, initialize it to 1
            if (isset($responseCounts[$response_text])) {
                $responseCounts[$response_text]++;
            } else {
                $responseCounts[$response_text] = 1;
            }
        }

        // Add responseCounts to response data
        $responseData["data"]["current"] = $responseCounts;


        $sql = "SELECT id, created_at FROM Backup WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $backup_id = $row['id'];
            $created_at = $row['created_at'];
            $sql = "SELECT response_text FROM BackupResponses WHERE backup_id = ?";
            ;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $backup_id);
            $stmt->execute();
            $result1 = $stmt->get_result();
            $responseCounts = [];
            while ($row1 = $result1->fetch_assoc()) {
                $response_text = $row1['response_text'];

                // If response_text exists in the array, increment its count; otherwise, initialize it to 1
                if (isset($responseCounts[$response_text])) {
                    $responseCounts[$response_text]++;
                } else {
                    $responseCounts[$response_text] = 1;
                }
            }
            // Add optionCounts to response data
            $responseData["data"][$created_at] = $responseCounts;
        }
    }
}

// Output the response data as JSON
echo json_encode($responseData, JSON_PRETTY_PRINT);

?>