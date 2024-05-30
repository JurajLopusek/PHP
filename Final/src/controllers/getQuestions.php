<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';
$username = $_SESSION["username"];
$sql = "SELECT user_id, role FROM Users WHERE username = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $username);

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row["user_id"];
    $role = $row["role"];
    


    if ($role === "admin") {
        $sql_questions = "SELECT q.question_id, u.username, q.question_text, q.subject, DATE(q.created_at) AS date, q.active, q.wordcloud, q.question_code, q.updated_at FROM Questions q JOIN Users u on u.user_id = q.user_id";
        $stmt_questions = $conn->prepare($sql_questions);
    } elseif ($role === "user") {
        $sql_questions = "SELECT q.question_id, u.username, q.question_text, q.subject, DATE(q.created_at) AS date, q.active, q.wordcloud, q.question_code, q.updated_at FROM Questions q JOIN Users u on u.user_id = q.user_id WHERE q.user_id = ?";
        $stmt_questions = $conn->prepare($sql_questions);
        $stmt_questions->bind_param("i", $user_id);
    }
    
    $stmt_questions->execute();

    $result_questions = $stmt_questions->get_result();

    while ($row_question = $result_questions->fetch_assoc()) {
        $questions[] = $row_question;
    }
    $questions_json = json_encode($questions, JSON_PRETTY_PRINT);

    // Nastavenie hlavičky pre výstup vo formáte JSON
    header('Content-Type: application/json');

    // Výpis JSON reprezentácie otázok
    echo $questions_json;
} else {
    echo "No results found.";
}
$conn =null;
//header('Content-Type: application/json');
//echo json_encode($result);






?>