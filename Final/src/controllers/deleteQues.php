<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../configFinal.php';
$question_id = $_GET["qid"]; 


$stmtDeleteQuestion = $conn->prepare("DELETE FROM Questions WHERE question_id = ?");
$stmtDeleteQuestion->bind_param("i", $question_id);

$stmtDeleteQuestion->execute();


$conn->close();

// Úspešná odpoveď
http_response_code(200);
echo json_encode(['success' => 'Otázka úspešne vložená a skopírovaná']);
?>
<script> window.location.href = "../index.php?action=ques-delete-success";</script>

