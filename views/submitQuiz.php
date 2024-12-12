<?php
session_start();
require_once "../classes/Quiz.php";
require_once "../classes/Question.php";
require_once "../classes/Result.php";
require_once "../utils/DbConnexion.php";

// Ensure user is authenticated (replace with your authentication logic)
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

// Get the user ID from session
$userId = $_SESSION['id'];

// Decode JSON payload
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['responses']) || empty($data['responses'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
    exit;
}

// Validate quiz ID
$quizId = $_SESSION['Play'] ?? null;
if (!$quizId) {
    echo json_encode(['success' => false, 'message' => 'Quiz ID not found in session.']);
    exit;
}

// Initialize classes
Quiz::init();
Question::init();
Result::create($userId, $quizId, 0);

// Fetch all questions for the quiz
$questions = Question::getByQuizId($quizId);
if (!$questions) {
    echo json_encode(['success' => false, 'message' => 'No questions found for the quiz.']);
    exit;
}

// Map questions by ID for quick lookup
$questionMap = [];
foreach ($questions as $question) {
    $questionMap[$question['id']] = $question;
}

// Calculate score
$totalQuestions = count($questions);
$correctAnswers = 0;

foreach ($data['responses'] as $response) {
    $questionId = $response['questionId'];
    $selectedAnswer = $response['selectedAnswer'];

    // Check if question exists and answer matches the correct one
    if (isset($questionMap[$questionId]) && $selectedAnswer === $questionMap[$questionId]['correctAnswer']) {
        $correctAnswers++;
    }
}

// Calculate percentage score
$percentageScore = ($correctAnswers / $totalQuestions) * 100;

// Save result in the database
Result::create($userId, $quizId, $percentageScore);

// Return result
echo json_encode([
    'success' => true,
    'message' => 'Quiz submitted successfully.',
    'score' => $percentageScore,
    'totalQuestions' => $totalQuestions,
    'correctAnswers' => $correctAnswers
]);
?>
