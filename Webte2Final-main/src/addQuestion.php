<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'header.php';
require '../../../configFinal.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    .hidden {
        display: none;
    }
</style>

<body>
    <div class="container mt-4">
        <form id="questionForm" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="question_type" class="form-label">
                    <h2 localize="select_question_type"></h2>
                </label>
                <select id="question_type" name="question_type" class="form-select" required>
                    <option value="open_ended" localize="open_question" style="user-select: none;"></option>
                    <option value="multiple_choice" localize="question_with_options"></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">
                    <h2 localize="subject"></h2>
                </label>
                <input type="text" id="subject" name="subject" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="question_text" class="form-label">
                    <h2 localize="question_text"></h2>
                </label>
                <input type="text" id="question_text" name="question_text" class="form-control" required>
            </div>
            <?php
            if ($_SESSION["role"] === "admin") {
                echo '
        <div class="mb-3">
            <label for="user" class="form-label">
                <h2 localize="add_to_user"></h2>
            </label>
            <select id="user" name="user" class="form-control">
        ';

                // Připravte a proveďte dotaz na seznam uživatelů
                $sql = "SELECT user_id, username FROM Users";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->execute();
                    $stmt->bind_result($user_id, $username);

                    // Iterujte přes výsledky a vytvořte možnosti (options)
                    while ($stmt->fetch()) {
                        echo "<option value=\"$user_id\">$username</option>";
                    }
                    $stmt->close();
                }

                echo '
            </select>
            </div>
        ';
            } ?>

            <div id="optionsContainer" class="hidden">
                <div class="mb-3">
                    <label class="form-label">
                        <h2 localize="answer_type"></h2>
                    </label>
                    <div>
                        <input type="radio" id="single_answer" name="answer_type" value="single" checked required>
                        <label for="single_answer" localize="single_correct_answer"></label>
                    </div>
                    <div>
                        <input type="radio" id="multiple_answers" name="answer_type" value="multiple" required>
                        <label for="multiple_answers" localize="multiple_correct_answers"></label>
                    </div>
                </div>

                <label>
                    <h2 localize="options"></h2>
                </label>
                <div id="optionFields">
                    <input type="text" name="options[]" class="form-control" placeholder="Option 1">
                    <input type="text" name="options[]" class="form-control" placeholder="Option 2">
                </div>
                <div class="centered">
                    <button type="button" id="addOption" class="btn btn-custom" localize="add_option"></button>
                    <button type="button" id="deleteOption" class="btn btn-custom" localize="delete_option"></button>
                </div>
            </div>

            <div class="centered">
                <button type="submit" id="submitButton" class="btn btn-custom" localize="submit_question"></button>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="alerts.js"></script>
    <script src="script.js"></script>

    <script>
        $(document).ready(function () {
            $('#question_type').change(function () {
                const type = $(this).val();
                if (type === 'multiple_choice') {
                    $('#optionsContainer').removeClass('hidden');
                    $('input[name="options[]"]').attr('required', true);  // Make options required when visible
                    $('input[name="answer_type"]').attr('required', true);
                    $('input[name="subject"]').attr('required', true);

                } else {
                    $('#optionsContainer').addClass('hidden');
                    $('input[name="options[]"]').removeAttr('required');  // Remove required attribute when not visible
                    $('input[name="answer_type"]').removeAttr('required');
                    $('input[name="subject"]').removeAttr('required');

                }
            });

            $('#addOption').click(function (event) {
                event.preventDefault();
                const optionNumber = $('#optionFields input').length + 1;
                const newOption = `<input type="text" name="options[]" class="form-control" placeholder="Option ${optionNumber}" required>`;  // Add required attribute
                $('#optionFields').append(newOption);
            });

            $('#deleteOption').click(function (event) {
                event.preventDefault();
                let options = $('#optionFields input');
                if (options.length > 2) {
                    options.last().remove();
                } else {
                    Swal.fire({
                        title: 'Attention!',
                        text: 'You must keep at least two options.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });

            $('#questionForm').submit(function (event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    const formData = {
                        question_text: $('#question_text').val(),
                        question_type: $('#question_type').val(),
                        user: $('#user').val(),
                        subject: $('#subject').val(),
                        options: $('#question_type').val() === 'multiple_choice' ? $('input[name="options[]"]').map(function () { return $(this).val(); }).get() : [],
                        answer_type: $('#question_type').val() === 'multiple_choice' ? $('input[name="answer_type"]:checked').val() : null
                    };

                    $.ajax({
                        type: "POST",
                        url: "controllers/question.php",
                        data: JSON.stringify(formData),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Question submitted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                }
                            });
                        },
                        error: function (err) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error submitting question!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
                this.classList.add('was-validated');
            });
        });

    </script>
    <script src="script.js"></script>
</body>

</html>