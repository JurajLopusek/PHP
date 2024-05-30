<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "login_control.php";
include_once 'header.php';
require 'configFinal.php';



$questionId = $_GET["qid"];
// Dotaz na získanie otázky
$sql = "SELECT * FROM Questions WHERE question_id='$questionId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $questionText = $row['question_text'];
    $subject = $row['subject'];
}

$sql_options = "SELECT * FROM QuestionOptions WHERE question_id='$questionId'";
$result_options = $conn->query($sql_options);

if ($result_options->num_rows > 0) {
    $options = array();
    while ($row_option = $result_options->fetch_assoc()) {
        $options[$row_option['option_id']] = $row_option['option_text'];
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<h2 localize="edit_q"></h2>
<div class="container mt-4">
    <form method="post" action="controllers/update.php">
        <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
        <label for="question_text" localize="text_q"></label><br>
        <input type="text" id="question_text" name="question_text" value="<?php echo $questionText; ?>">
        <label for="subject" localize="subject"></label><br>
        <input type="text" name="subject" value="<?php echo $subject; ?>"><br><br>
        <?php
        if (!empty($options)) {
            echo "<h3 localize='options'></h3>";
            foreach ($options as $optionId => $optionText) {
                echo "<input type='text' name='options[$optionId]' value='$optionText'><br><br>";
            }
        }
        ?>
        <input type="submit" value="Uložiť zmeny">
    </form>
</div>
