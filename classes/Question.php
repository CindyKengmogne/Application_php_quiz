<?php

require_once "../utils/DbConnexion.php";

class Question {
    
    private static PDO $db;
    private $id;
    private $questionText;
    private $correctAnswer;
    private $answers;

    public function __construct($id, $questionText, $correctAnswer, $answers) {
        $this->id = $id;
        $this->questionText = $questionText;
        $this->correctAnswer = $correctAnswer;
        $this->answers = explode(';', $answers); // Convertir la chaîne en tableau
    }



    public function getId() {
        return $this->id;
    }

    public function getQuestionText() {
        return $this->questionText;
    }

    public function getCorrectAnswer() {
        return $this->correctAnswer;
    }

    public function answers() {
        return $this->answers;
    }
    public static function init() {
        self::$db=DbConnexion::getConnection();
    }

    public static function create($quizId, $questionText, $imageUrl, $correctAnswer, $answers) {
        self::$db=DbConnexion::getConnection();

        $stmt = self::$db->prepare("INSERT INTO questions (quizId,questionText, imageUrl, correctAnswer, answers) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$quizId, $questionText, $imageUrl, $correctAnswer, $answers]);
    }

    public static function findById($id) {
        self::$db=DbConnexion::getConnection();

        $stmt = self::$db->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $questionText, $imageUrl, $correctAnswer, $answers) {
                self::$db=DbConnexion::getConnection();

        $stmt = self::$db->prepare("UPDATE questions SET questionText = ?, imageUrl = ?, correctAnswer = ?, answers = ? WHERE id = ?");
        return $stmt->execute([$questionText, $imageUrl, $correctAnswer, json_encode($answers), $id]);
    }

    public static function delete($id) {
                self::$db=DbConnexion::getConnection();

        $stmt = self::$db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getByQuizId($quizId) {
                self::$db=DbConnexion::getConnection();

        $stmt = self::$db->prepare("SELECT * FROM questions WHERE quizId = ?");
        $stmt->execute([$quizId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}