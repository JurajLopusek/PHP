<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';

// Funkcia na export otázok a odpovedí vo formáte JSON
// Funkcia na export otázok a odpovedí vo formáte JSON
function exportQuestionsAndResponses($userId, $conn)
{
    // Overenie pripojenia
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Pole pre ukladanie otázok a odpovedí vo formáte JSON
    $questionsAndResponses = [];

    // Pre admina získa všetky otázky a odpovede
    if ($_SESSION['role'] == 'admin') {
        $sql = "SELECT * FROM Questions";
    } else { // Pre používateľa získa iba jeho otázky a odpovede
        $sql = "SELECT * FROM Questions WHERE user_id = $userId";
    }

    // Vykonanie SQL dotazu na otázky
    $result10 = $conn->query($sql);

    // Spracovanie výsledkov
    if ($result10->num_rows > 0) {
        // Pre každý riadok výsledku (otázka)
        while ($row10 = $result10->fetch_assoc()) {
            $question = [
                "question_id" => $row10["question_id"],
                "question_text" => $row10["question_text"],
                "responses" => [] // Pole pre ukladanie odpovedí k otázke
            ];

            $question_id = $row10["question_id"];

            $responseData = [];
            $question_type = $row10["question_type"];
            $question_text = $row10["question_text"];

            // Add question_text to response data
            $responseData = [];

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
                $responseData["current"] = $optionCounts;


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
                    $responseData[$created_at] = $optionCounts;
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
                $responseData["current"] = $responseCounts;


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
                    $responseData[$created_at] = $responseCounts;
                }
            }
            $question['responses']= $responseData;




            // Pridá otázku do hlavného poľa
            $questionsAndResponses[] = $question;
        }
    }

    // Konvertuje pole otázok a odpovedí na JSON a vypíše ho
    $json = json_encode($questionsAndResponses, JSON_PRETTY_PRINT);
    
    // Hlavičky pre sťahovanie JSON súboru
    header('Content-Description: File Transfer');
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="questions_and_responses.json"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($json));

    // Vypíše JSON do výstupu (sťahuje súbor)
    echo $json;
}

// Zavolanie funkcie na export otázok a odpovedí
// Pre admina použije všetky otázky a odpovede
// Pre bežného používateľa iba jeho otázky a odpovede
if ($_SESSION['role'] == 'admin') {
    exportQuestionsAndResponses(null, $conn);
} else {
    exportQuestionsAndResponses($_SESSION['user_id'], $conn);
}

// Uzavretie pripojenia k databáze
$conn->close();
?>