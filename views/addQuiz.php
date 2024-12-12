<?php
require_once "../classes/Quiz.php";
require_once "../classes/Question.php";
require_once "../utils/DbConnexion.php";

// Check if we're editing a quiz
$editQuizId = $_GET['edit'] ?? null;
$quiz = null;
$questions = [];

if ($editQuizId) {
    Quiz::init();
    Question::init();

    // Fetch the quiz and its questions
    $quiz = Quiz::findById($editQuizId);
    $questions = Question::getByQuizId($editQuizId);

    if (!$quiz) {
        echo "Error: Quiz not found.";
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $quizId = $_POST["quizId"] ?? null; // Hidden field to identify if it's an edit
    $title = $_POST["quizTitle"];
    $description = $_POST["quizDescription"];
    $questions = $_POST["questionText"] ?? [];
    $images = $_POST["imageUrl"] ?? [];
    $allAnswers = $_POST["answer"] ?? [];
    $correctAnswers = $_POST["correctAnswer"] ?? [];

    // Validate required fields
    if (empty($title) || empty($description)) {
        echo "Error: Title and description are required.";
        exit;
    }

    // Establish database connection
    $conn = DbConnexion::getConnection();

    try {
        // Begin transaction
        $conn->beginTransaction();

        if ($quizId) {
            // Update the existing quiz
            Quiz::update($quizId, $title, $description);

            // Remove existing questions for this quiz
            $stmt = $conn->prepare("DELETE FROM questions WHERE quizId = ?");
            $stmt->execute([$quizId]);
        } else {
            // Create a new quiz
            Quiz::create($title, $description);
            $quiz = Quiz::findByTitle($title);
            $quizId = $quiz['id'];
        }

        // Insert or update questions
        foreach ($questions as $index => $questionText) {
            $imageUrl = $images[$index] ?? '';
            $answers = $allAnswers[$index] ?? [];
            $correctAnswerIndex = $correctAnswers[$index] ?? null;

            // Validate question data
            if (empty($questionText) || !is_array($answers) || $correctAnswerIndex === null) {
                throw new Exception("Invalid question data at index $index.");
            }

            // Combine answers into a semicolon-separated string
            $answerString = implode(";", $answers);

            // Create the question
            Question::create($quizId, $questionText, $imageUrl, $correctAnswerIndex, $answerString);
        }

        // Commit transaction
        $conn->commit();

        echo $quizId ? "Quiz updated successfully!" : "Quiz created successfully!";
        header("Location: quizzesAdmin.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer/Modifier un Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .quiz-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .question-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 5px;
        }

        .question-section h3 {
            margin-top: 0;
        }

        .remove-question {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .answer-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-add {
            background-color: #2196F3;
        }

        .btn-remove {
            background-color: #f44336;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>

    <head>
        <script>
            let questionCount = 0;

            function createQuestionSection(questionData = null) {
                questionCount++;
                const questionSection = document.createElement('div');
                questionSection.classList.add('question-section');
                questionSection.dataset.questionNumber = questionCount;

                // HTML content for the question section
                questionSection.innerHTML = `
        <h3>Question ${questionCount}</h3>
        <button type="button" class="remove-question" onclick="removeQuestion(this)">✖ Supprimer</button>

        <div class="form-group">
            <label for="questionText${questionCount}">Texte de la Question</label>
            <input type="text" id="questionText${questionCount}" name="questionText[]" required placeholder="Entrez le texte de la question" value="${questionData ? questionData.questionText : ''}">
        </div>

        <div class="form-group">
            <label for="imageUrl${questionCount}">URL de l'Image (optionnel)</label>
            <input type="text" id="imageUrl${questionCount}" name="imageUrl[]" placeholder="Lien vers une image liée à la question" value="${questionData ? questionData.imageUrl : ''}">
        </div>

        <div class="form-group">
            <label>Réponses Possibles</label>
            <div class="answer-options">
                <!-- Answer inputs will be dynamically added here -->
            </div>
        </div>

        <div class="form-group">
            <label for="correctAnswer${questionCount}">Réponse Correcte</label>
            <select id="correctAnswer${questionCount}" name="correctAnswer[]" required>
                <option value="">Sélectionnez la réponse correcte</option>
            </select>
        </div>
    `;

                const answerContainer = questionSection.querySelector('.answer-options');
                const correctAnswerSelect = questionSection.querySelector(`#correctAnswer${questionCount}`);

                if (questionData && questionData.answers) {
                    // Populate existing answers and correct answer
                    questionData.answers.split(';').forEach((answer) => {
                        // Add input for the answer
                        const answerInput = document.createElement('input');
                        answerInput.type = 'text';
                        answerInput.name = `answer[${questionCount - 1}][]`;
                        answerInput.value = answer;
                        answerInput.required = true;
                        answerInput.placeholder = 'Option';
                        answerInput.setAttribute('oninput', `updateCorrectAnswerOptions(${questionCount})`);
                        answerContainer.appendChild(answerInput);

                        // Add option to the correct answer dropdown
                        const option = document.createElement('option');
                        option.value = answer;
                        option.textContent = answer;
                        if (answer === questionData.correctAnswer) {
                            option.selected = true;
                        }
                        correctAnswerSelect.appendChild(option);
                    });
                } else {
                    // Create 4 empty answer inputs for new questions
                    for (let i = 0; i < 4; i++) {
                        const answerInput = document.createElement('input');
                        answerInput.type = 'text';
                        answerInput.name = `answer[${questionCount - 1}][]`;
                        answerInput.required = true;
                        answerInput.placeholder = `Option ${i + 1}`;
                        answerInput.setAttribute('oninput', `updateCorrectAnswerOptions(${questionCount})`);
                        answerContainer.appendChild(answerInput);
                    }
                }

                return questionSection;
            }


            function removeQuestion(button) {
                const questionSection = button.parentElement;
                questionSection.remove();

                const questionSections = document.querySelectorAll('.question-section');
                questionSections.forEach((section, index) => {
                    section.dataset.questionNumber = index + 1;
                    section.querySelector('h3').textContent = `Question ${index + 1}`;
                });

                questionCount = questionSections.length;
            }
        </script>
    </head>

</head>

<body>
    <form action="" id="quizForm" class="quiz-form" method="POST">
        <!-- <h2>Créer/Modifier un Quiz</h2> -->
        <h2><?php echo $editQuizId ? "Créer Quiz" : "Modifier Quiz"; ?></h2>

        <!-- Hidden field for quizId -->
        <?php if ($editQuizId): ?>
            <input type="hidden" name="quizId" value="<?php echo $editQuizId; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="quizTitle">Quiz Title</label>
            <input type="text" id="quizTitle" name="quizTitle"
                value="<?php echo htmlspecialchars($quiz['title'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="quizDescription">Quiz Description</label>
            <textarea id="quizDescription" name="quizDescription"
                required><?php echo htmlspecialchars($quiz['description'] ?? ''); ?></textarea>
        </div>

        <div id="questionsContainer">
            <?php if (!empty($questions)): ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const questions = <?php echo json_encode($questions); ?>;

                        if (questions.length > 0) {
                            // Populate existing questions for editing
                            questions.forEach((question) => {
                                const questionData = {
                                    questionText: question.questionText,
                                    imageUrl: question.imageUrl,
                                    answers: question.answers,
                                    correctAnswer: question.correctAnswer
                                };

                                const questionSection = createQuestionSection(questionData);
                                document.getElementById("questionsContainer").appendChild(questionSection);
                            });
                        } else {
                            // Add an empty question section for quiz creation
                            document.getElementById("questionsContainer").appendChild(createQuestionSection());
                        }
                    });

                </script>


            <?php endif; ?>
        </div>

        <button type="button" id="addQuestionBtn" class="btn btn-add">
            + Ajouter une Question
        </button>
        <button type="submit" class="btn">
            Enregistrer le Quiz
        </button>
        </div>
    </form>
    <script>



        function updateCorrectAnswerOptions(questionNumber) {
            // Select all answer inputs for the current question
            const answerInputs = document.querySelectorAll(
                `[name="answer[${questionNumber - 1}][]"]`
            );

            // Select the correct answer dropdown for the current question
            const correctAnswerSelect = document.getElementById(`correctAnswer${questionNumber}`);

            // Clear existing options
            correctAnswerSelect.innerHTML = '<option value="">Sélectionnez la réponse correcte</option>';

            // Populate the dropdown with updated answers
            answerInputs.forEach((input) => {
                if (input.value.trim() !== "") {
                    const option = document.createElement("option");
                    option.value = input.value; // Use the input value as the option value
                    option.textContent = input.value; // Display the input value as the option text
                    correctAnswerSelect.appendChild(option);
                }
            });
        }

        document.getElementById('addQuestionBtn').addEventListener('click', () => {
            document.getElementById('questionsContainer').appendChild(createQuestionSection());
        });
    </script>

</body>

</html>