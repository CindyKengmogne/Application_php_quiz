<?php

require_once "../utils/DbConnexion.php";

class Result {
    private  static PDO $db;
    private $id;
    private $userId;
    private $quizId;
    private $score;
    private $date;

    public  function __construct($db) {
        self::$db= DbConnexion::getConnection();
    }

    public static function create($userId, $quizId, $score) {
        self::$db= DbConnexion::getConnection();
        $stmt = self::$db->prepare("INSERT INTO results (userId, quizId, score) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $quizId, $score]);
    }

    public static function getByUserId($userId) {
        self::$db= DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT * FROM results WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        self::$db= DbConnexion::getConnection();
        $stmt = self::$db->query("SELECT * FROM results");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>