<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<?php
ob_start(); // Start output buffering
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../../configFinal.php';
include_once 'header.php';

$questionCode = $_GET['questionCode'] ?? '';  // Safely fetch the question code

// Prepare and execute the query to fetch the question details
if ($stmt = $conn->prepare("SELECT * FROM Questions WHERE question_code = ?")) {
    $stmt->bind_param("s", $questionCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a question was actually fetched
    if ($question = $result->fetch_assoc()) {
        // Check the type of question and fetch options if it's multiple choice
        if ($question['question_type'] == 'multiple_choice') {
            if ($optionsStmt = $conn->prepare("SELECT option_id, option_text FROM QuestionOptions WHERE question_id = ?")) {
                $optionsStmt->bind_param("i", $question['question_id']);
                $optionsStmt->execute();
                $optionsResult = $optionsStmt->get_result();
                $options = $optionsResult->fetch_all(MYSQLI_ASSOC);
            }
        }
    } else {
        header("location: /Webte2Final/src/index.php?action=code-not-found");
        exit;
    }
    $stmt->close();
} else {
    echo "SQL Error ";
    exit;
}
?>

<div class="row justify-content-center" style="margin-top: 5rem;">
    <div class="col-md-8">
        <form id="answerForm" action="controllers/submitAnswer.php" method="post" class="needs-validation" novalidate>
            <!-- Added class="needs-validation" and novalidate attribute for Bootstrap validation -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" localize="question_detail"></h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($question)): ?>
                        <h2 style="text-align:left;" localize="question"></h2>
                        <p class="card-text"><?php echo htmlspecialchars($question['question_text']); ?></p>

                        <?php if ($question['question_type'] == 'multiple_choice' && !empty($options)): ?>
                            <div class="mt-4">
                                <h2 style="text-align:left;" localize="select_answer"></h2>
                                <?php if ($question['options_count'] == 'single'): ?>
                                    <?php foreach ($options as $option): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answer"
                                                id="option<?php echo htmlspecialchars($option['option_id']); ?>"
                                                value="<?php echo htmlspecialchars($option['option_id']); ?>" required>
                                            <label class="form-check-label"
                                                for="option<?php echo htmlspecialchars($option['option_id']); ?>">
                                                <?php echo htmlspecialchars($option['option_text']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif ($question['options_count'] == 'multiple'): ?>
                                    <?php foreach ($options as $option): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="answer[]"
                                                id="option<?php echo htmlspecialchars($option['option_id']); ?>"
                                                value="<?php echo htmlspecialchars($option['option_id']); ?>">
                                            <label class="form-check-label"
                                                for="option<?php echo htmlspecialchars($option['option_id']); ?>">
                                                <?php echo htmlspecialchars($option['option_text']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="container mt-4">
                                <div class="mb-3">
                                    <label for="openAnswer" class="form-label">
                                        <h2 localize="your_answer"></h2>
                                    </label>
                                    <input type="text" class="form-control" id="openAnswer" name="answer" required>
                                    <div class="invalid-feedback">Please provide an answer.</div>
                                    <!-- Added invalid feedback for Bootstrap validation -->
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="card-text" localize="no_question_found"></p>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <!-- Hidden input to carry the question ID -->
                    <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
                    <button type="submit" class="btn btn-primary" localize="submit_answer"></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="alerts.js"></script>
<script src="script.js"></script>
<script>
    $(document).ready(function () {
        $('#answerForm').submit(function (e) {
            e.preventDefault(); // Prevent default form submission

            // Validate the form using Bootstrap's built-in validation
            if (this.checkValidity() === false) {
                e.stopPropagation();
            } else {
                var questionId = $("input[name='question_id']").val();
                var answers;

                // Handle multiple answer submissions if checkboxes are used
                if ($("input[type='checkbox'][name='answer[]']").length > 0) {
                    answers = [];
                    $("input[type='checkbox'][name='answer[]']:checked").each(function () {
                        answers.push($(this).val());
                    });
                } else {
                    // It's either a single choice or open-ended question
                    answers = $("input[type='radio'][name='answer']:checked, #openAnswer").val();
                }

                // Create JSON object for the form data
                var formData = {
                    question_id: questionId,
                    answer: answers
                };

                // AJAX request with JSON data
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: JSON.stringify(formData),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            title: 'Answer submitted!',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false // Prevent dismissing on click outside
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `answers.php?qid=${questionId}`;
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: 'Oops..',
                            text: 'Answer was not submitted: ' + error,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            }

            // Add Bootstrap's was-validated class to trigger validation styling
            $(this).addClass('was-validated');
        });
    });
</script>

</body>

</html>
<?php
ob_end_flush(); // Flush the output buffer and send to the client
?>