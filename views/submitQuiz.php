<?php
session_start();
require_once "../classes/Quiz.php";
require_once "../classes/Question.php";
require_once "../utils/DbConnexion.php";

// Récupérer les données du quiz
$quizId = $_SESSION['Play'];
$quiz = Quiz::findById($quizId);
$questions = Question::getByQuizId($quizId);

// Récupérer les réponses du joueur
$responses = json_decode($_POST['responses'], true);

// Calculer le score
$score = 0;
foreach ($responses as $response) {
    $questionId = $response['questionId'];
    $question = Question::findById($questionId);
    $correctAnswer = $question['correctAnswer'];
    if ($response['score'] === $correctAnswer) {
        $score++;
    }
}

echo $score;

// Enregistrer le résultat dans la table Results
//$conn = DbConnexion::getInstance();
//$query = "INSERT INTO Results (quiz_id, user_id, score) VALUES ('$quizId', '".$_SESSION['id']."', '$score')";
//$result = $conn->query($query);

// Diriger vers une page qui affiche le score
//if ($result) {
//    header('Location: score.php?score='.$score);
//    exit;
//} else {
//    echo "Erreur lors de l'enregistrement du résultat.";
//    exit;

?>