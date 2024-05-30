<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Načítajte údaje z tela požiadavky
    $question_id = $_POST['question_id']; // ID otázky
    $note = $_POST['note']; // Poznámkaa

    $sql_select_responses = "SELECT * FROM Questions WHERE question_id = $question_id";
    $result = $conn->query($sql_select_responses);

    $sql_select_responses = "SELECT * FROM Responses WHERE question_id = $question_id";
    $result_response = $conn->query($sql_select_responses);
    if ($result_response->num_rows == 0) {
        http_response_code(200); // Nastavenie HTTP stavového kódu na 200 (OK)
        header('Content-Type: application/json'); // Nastavenie hlavičky pre JSON odpoveď
        echo json_encode(['message' => "no_need"]);
    } else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sql = "INSERT INTO Backup(question_id, note, created_at) VALUES (?,?,NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $question_id, $note);
                if (!$stmt->execute()) {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
                    $stmt->close();
                    $conn->close();
                    exit;
                }
            }
            $backup_id = $stmt->insert_id;
            $stmt->close();
            if ($result_response->num_rows > 0) {
                while ($row = $result_response->fetch_assoc()) {
                    $user_id = $row['user_id'];
                    $option_id = $row['option_id'];
                    $response_text = $row['response_text'];
                    $sql_insert_backup_response = "INSERT INTO BackupResponses (backup_id, user_id, option_id, response_text) VALUES (?, ?, ?, ?)";
                    $stmt_insert_response = $conn->prepare($sql_insert_backup_response);
                    $stmt_insert_response->bind_param("iiis", $backup_id, $user_id, $option_id, $response_text);
                    $stmt_insert_response->execute();
                }
                $sql_delete_responses = "DELETE FROM Responses WHERE question_id = $question_id";
                $conn->query($sql_delete_responses);
                http_response_code(200); // Nastavenie HTTP stavového kódu na 200 (OK)
                header('Content-Type: application/json'); // Nastavenie hlavičky pre JSON odpoveď
                echo json_encode(['message' => "ok"]);
            }
        }
    }
    $conn->close();
}
?>