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

$countquestion = count($questions);
$compt = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            pointer-events:none;
        }
        /*.correct {*/
        /*    color: green !important;*/
        /*    font-weight: bold;*/
        /*}*/
        /*.incorrect {*/
        /*    color: red !important;*/
        /*    font-weight: bold;*/
        /*}*/
        /*.disabled-answer {*/
        /*    pointer-events: none;*/
        /*}*/
    </style>
</head>
<body>
<div class="main-container">
    <div class="game" id="game">
        <div class="quiz">
            <div class="quiz-title">
                <p><?php echo $quiz['title']; ?></p>
            </div>
            <?php foreach ($questions as $index => $question) : ?>
                <?php
                $answers = explode(";", $question['answers']);
                $correctAnswer = $question['correctAnswer'];
                ?>
                <div class="question-container" data-question-id="<?php echo $question['id']; ?>"
                     data-correct-answer="<?php echo htmlspecialchars($correctAnswer, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="question-text">
                        <p><?php echo htmlspecialchars($question['questionText'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if (!empty($question['imageUrl'])): ?>
                            <img src="<?php echo htmlspecialchars($question['imageUrl'], ENT_QUOTES, 'UTF-8'); ?>"
                                 alt="Question Image" width="50" height="50">
                        <?php endif; ?>
                    </div>
                    <?php foreach ($answers as $answerIndex => $answer): ?>
                        <div class="answer">
                            <strong><?php echo chr(65 + $answerIndex); ?>.</strong>&nbsp;
                            <a href="#" class="ans" data-answer="<?php echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?>"
                               data-index="<?php echo $answerIndex; ?>">
                                <?php echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

<!--            --><?php //foreach ($questions as $index => $question) : ?>
<!--                --><?php
//                $answers = explode(";", $question['answers']);
//                $correctAnswer = $question['correctAnswer'];
//                ?>
<!--                <p id="correctAnswer" style="visibility: hidden;">--><?php //echo $correctAnswer; ?><!--</p>-->
<!---->
<!--                <div class="question-container" data-question-id="--><?php //echo $question['id']; ?><!--">-->
<!--                    <div class="question-text">-->
<!--                        <p>--><?php //echo $question['questionText']; ?><!--</p>-->
<!--                        <img src="--><?php //echo $question['imageUrl']; ?><!--" alt="No img" width="50" height="50">-->
<!--                    </div>-->
<!--                    --><?php //foreach ($answers as $answerIndex => $answer): ?>
<!--                        <div id="answers--><?php //echo $answerIndex + 1; ?><!--">-->
<!--                            <strong>--><?php //echo chr(65 + $answerIndex); ?><!--.</strong>&nbsp;-->
<!--                            <a href="#" class="ans" data-answer="--><?php //echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?><!--"-->
<!--                               data-index="--><?php //echo $answerIndex; ?><!--">-->
<!--                                --><?php //echo htmlspecialchars($answer, ENT_QUOTES, 'UTF-8'); ?>
<!--                            </a>-->
<!--                        </div>-->
<!--                    --><?php //endforeach; ?>
<!--                </div>-->
<!--            --><?php //endforeach; ?>

            <div class="exit">
                <a href="dashboardPlayer.php" class="bttn" id="cancelQuiz">Quitter</a>

            </div>
            <div class="submit-container">
                <button id="submitQuiz">Soumettre le Quiz</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questionContainers = document.querySelectorAll('.question-container');
        const submitButton = document.getElementById('submitQuiz');
        let score = 0;
        questionContainers.forEach(container => {
            const answers = container.querySelectorAll('.ans');
            answers.forEach(answer => {
                answer.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Disable all answer links for this question
                    answers.forEach(a => a.classList.add('disabled-answer'));

                    // Highlight the selected answer
                    const selectedAnswer = this.getAttribute('data-answer');
                    const correctAnswer = container.getAttribute('data-correct-answer');

                    if (selectedAnswer === correctAnswer) {
                        this.classList.add('correct');
                        score++;
                    } else {
                        this.classList.add('incorrect');
                        // Highlight correct answer
                        answers.forEach(a => {
                            if (a.getAttribute('data-answer') === correctAnswer) {
                                a.classList.add('correct');
                            }
                        });
                    }
                });
            });
        });

        // Submit the quiz
        submitButton.addEventListener('click', function () {
            const responses = [];
            questionContainers.forEach(container => {
                const questionId = container.getAttribute('data-question-id');
                const selectedAnswer = Array.from(container.querySelectorAll('.ans.correct')).map(a => a.getAttribute('data-answer'))[0];
                responses.push({ questionId, score });
            });

            // Send AJAX request
            fetch('submitQuiz.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ responses })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Quiz soumis avec succès!');
                    } else {
                        alert('Erreur lors de la soumission du quiz.');
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