<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../header.php';
require '../configFinal.php';


    // Získanie hodnôt z formulára
    $questionId = $_POST['question_id'];
    $newQuestionText = $_POST['question_text'];
    $newSubject = $_POST['subject'];

    // Aktualizácia otázky v databáze
    $sql = "UPDATE Questions SET question_text='$newQuestionText', subject='$newSubject', updated_at = NOW() WHERE question_id='$questionId'";
    if ($conn->query($sql) === TRUE) {
        echo "Otázka bola úspešne aktualizovaná.";
    } else {
        echo "Chyba pri aktualizácii otázky: " . $conn->error;
    }

    // Spracovanie možností (options)
    if (isset($_POST['options'])) {
        $options = $_POST['options'];
        foreach ($options as $optionId => $newOptionText) {
            // Aktualizácia možnosti v databáze
            $sql = "UPDATE QuestionOptions SET option_text='$newOptionText' WHERE option_id='$optionId'";
            $conn->query($sql);
        }
        echo "Možnosti boli úspešne aktualizované.";
    }

    $conn->close();
    // Presmerovanie na inú stránku po spracovaní formulára
?>
<script> window.location.href = "../index.php?action=ques-update-success";</script>
<?php
    exit;



?>