<?php
session_start();
require_once "../classes/Quiz.php";
require_once "../classes/Question.php";
require_once "../utils/DbConnexion.php";

if (!isset($_SESSION['Play'])) {
    echo json_encode(['error' => 'ID du quiz non défini dans la session.']);
    exit;
}

$quizId = $_SESSION['Play'];
$quiz = Quiz::findById($quizId);
$questions = Question::getByQuizId($quizId);
$countQuestions = count($questions);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <link rel="stylesheet" href="../static/Play.css">
    <style>
        .correct {
            color: green !important;
            font-weight: bold;
        }

        .incorrect {
            color: red !important;
            font-weight: bold;
        }

        .disabled-answer {
            pointer-events: none;
        }

        .question-container {
            display: none;
        }

        .question-container.active {
            display: block;
        }

        .main-container {
            text-align: center;
        }

        .results-container {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        .results-container p {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .action-buttons {
            margin-top: 20px;
        }

        .action-buttons button {
            padding: 10px 15px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
        }

        #retakeQuiz {
            background-color: #4CAF50;
            color: white;
        }

        #leaveQuiz {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="quiz">
            <div class="quiz-title">
                <p><?php echo $quiz['title']; ?></p>
            </div>
            <div id="questionWrapper">
                <?php foreach ($questions as $index => $question): ?>
                    <?php
                    $answers = explode(";", $question['answers']);
                    $correctAnswer = $question['correctAnswer'];
                    ?>
                    <div class="question-container" data-question-id="<?php echo $question['id']; ?>"
                        data-correct-answer="<?php echo htmlspecialchars($correctAnswer, ENT_QUOTES, 'UTF-8'); ?>"
                        data-index="<?php echo $index; ?>">
                        <div class="question-text">
                            <p><?php echo htmlspecialchars($question['questionText'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php if (!empty($question['imageUrl'])): ?>
                                <img src="<?php echo htmlspecialchars($question['imageUrl'], ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="Question Image" width="150" height="150">
                            <?php endif; ?>
                        </div>
                        <?php foreach ($answers as $answerIndex => $answer): ?>
                            <div class="answer">
                                <strong><?php echo chr(65 + $answerIndex); ?>.</strong>&nbsp;
                                <a href="#" class="ans"
                                    data-answer="<?php echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="controls">
                <button id="prevQuestion" disabled>Précédent</button>
                <button id="nextQuestion">Suivant</button>
            </div>
            <div class="submit-container" style="display: none;">
                <button id="submitQuiz">Soumettre le Quiz</button>
            </div>
            <div class="exit">
                <a href="dashboardPlayer.php" class="bttn">Quitter</a>
            </div>
        </div>
    </div>


    <div class="results-container" id="resultsContainer">
        <p id="resultsText"></p>
        <div class="action-buttons">
            <button id="retakeQuiz" onclick="location.reload();">Reprendre le Quiz</button>
            <button id="leaveQuiz"  onclick="window.location.href='dashboardPlayer.php';">Quitter</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const questions = document.querySelectorAll('.question-container');
            const totalQuestions = questions.length;
            let currentQuestionIndex = 0;
            const responses = [];

            // Show the first question
            questions[currentQuestionIndex].classList.add('active');

            // Handle answer selection
            document.querySelectorAll('.ans').forEach(answer => {
                answer.addEventListener('click', function (e) {
                    e.preventDefault();

                    const container = this.closest('.question-container');
                    const selectedAnswer = this.getAttribute('data-answer');
                    const correctAnswer = container.getAttribute('data-correct-answer');

                    // Disable all answers
                    container.querySelectorAll('.ans').forEach(ans => ans.classList.add('disabled-answer'));

                    // Highlight the selected answer
                    if (selectedAnswer === correctAnswer) {
                        this.classList.add('correct');
                    } else {
                        this.classList.add('incorrect');
                        container.querySelector(`.ans[data-answer="${correctAnswer}"]`).classList.add('correct');
                    }

                    // Record response
                    const questionId = container.getAttribute('data-question-id');
                    responses.push({ questionId, selectedAnswer });
                });
            });

            // Navigation buttons
            document.getElementById('nextQuestion').addEventListener('click', function () {
                if (currentQuestionIndex < totalQuestions - 1) {
                    questions[currentQuestionIndex].classList.remove('active');
                    currentQuestionIndex++;
                    questions[currentQuestionIndex].classList.add('active');

                    document.getElementById('prevQuestion').disabled = false;

                    if (currentQuestionIndex === totalQuestions - 1) {
                        this.style.display = 'none';
                        document.querySelector('.submit-container').style.display = 'block';
                    }
                }
            });

            document.getElementById('prevQuestion').addEventListener('click', function () {
                if (currentQuestionIndex > 0) {
                    questions[currentQuestionIndex].classList.remove('active');
                    currentQuestionIndex--;
                    questions[currentQuestionIndex].classList.add('active');

                    document.getElementById('nextQuestion').style.display = 'inline';
                    document.querySelector('.submit-container').style.display = 'none';

                    if (currentQuestionIndex === 0) {
                        this.disabled = true;
                    }
                }
            });

            // Submit the quiz
            document.getElementById('submitQuiz').addEventListener('click', function () {
                fetch('submitQuiz.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ responses })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Display results
                            const resultsContainer = document.getElementById('resultsContainer');
                            const resultsText = document.getElementById('resultsText');
                            resultsContainer.style.display = 'block';

                            resultsText.innerHTML = `Vous avez répondu correctement à ${data.correctAnswers}/${data.totalQuestions} questions. Votre score est ${data.score.toFixed(2)}%.`;

                            document.querySelector('.quiz').style.display = 'none';
                        } else {
                            alert('Erreur: ' + (data.message ?? 'Une erreur est survenue.'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la soumission.');
                    });
            });
        });
    </script>
</body>

</html>