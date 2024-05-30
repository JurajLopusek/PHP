<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';
$question_id = $_GET["qid"]; 

// Generovanie náhodného 5-znakového kódu
$question_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

// Nájdenie unikátneho kódu pre novú otázku
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


// Získanie údajov existujúcej otázky na základe question_id
$stmtCopy = $conn->prepare("SELECT * FROM Questions WHERE question_id = ?");
$stmtCopy->bind_param("i", $question_id);
$stmtCopy->execute();
$resultCopy = $stmtCopy->get_result();

if ($resultCopy->num_rows > 0) {
    $rowCopy = $resultCopy->fetch_assoc();

    // Vloženie údajov existujúcej otázky do nového záznamu (bez kódu)
    $stmtInsertCopy = $conn->prepare("INSERT INTO Questions (user_id, question_text, question_type, subject, options_count,  active, question_code, created_at, updated_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())");
    $stmtInsertCopy->bind_param("issssis", $rowCopy['user_id'], $rowCopy['question_text'], $rowCopy['question_type'], $rowCopy['subject'], $rowCopy['options_count'], $rowCopy['active'], $question_code);
    $stmtInsertCopy->execute();
    $newQuesId = $stmtInsertCopy->insert_id;
    $stmtInsertCopy->close();
}

if ($rowCopy['question_type'] == 'multiple_choice') {

    $stmtGetOptions = $conn->prepare("SELECT option_text FROM QuestionOptions WHERE question_id = ?");
    $stmtGetOptions->bind_param("i", $question_id);
    $stmtGetOptions->execute();
    $resultOptions = $stmtGetOptions->get_result();

    // Vloženie každej možnosti odpovede do nového záznamu otázky
    while ($rowOption = $resultOptions->fetch_assoc()) {
        $option_text = $rowOption['option_text'];

        // Vloženie možnosti odpovede do nového záznamu otázky
        $stmtInsertOption = $conn->prepare("INSERT INTO QuestionOptions (question_id, option_text) VALUES (?, ?)");
        $stmtInsertOption->bind_param("is", $newQuesId, $option_text);
        $stmtInsertOption->execute();
        $stmtInsertOption->close();
    }

    $stmtGetOptions->close();
}
$stmtCopy->close();

$conn->close();

// Úspešná odpoveď
http_response_code(200);
echo json_encode(['success' => 'Otázka úspešne vložená a skopírovaná']);

?>
<script> window.location.href = "../index.php?action=ques-copy-success";</script>

