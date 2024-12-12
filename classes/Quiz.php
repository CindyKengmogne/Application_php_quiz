<?php

require_once "../utils/DbConnexion.php";
class Quiz {
    private  static ?PDO $db=null;
    public $id;
    public $title;
    public $description;
    public $dateCreation;

    public static function init()  {
        self::$db = DbConnexion::getConnection();

        if (self::$db === null) {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    }

    public static function create($title, $description) {
        self::$db = DbConnexion::getConnection();

        $stmt = self::$db->prepare("INSERT INTO quiz (title, description) VALUES (?, ?)");
        return $stmt->execute([$title, $description]);
    }

    public static function findById($id) {
        self::$db = DbConnexion::getConnection();

        $stmt = self::$db->prepare("SELECT * FROM quiz WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function findByTitle($title) {
    self::$db = DbConnexion::getConnection();

    $stmt = self::$db->prepare("SELECT * FROM quiz WHERE title = ?");
    $stmt->execute([$title]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    
    public static function update($id, $title, $description) {
        self::$db = DbConnexion::getConnection();

        $stmt = self::$db->prepare("UPDATE quiz SET title = ?, description = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $id]);
    }

    public static function delete($id) {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("DELETE FROM quiz WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getAll() {
        self::$db = DbConnexion::getConnection();

        $stmt = self::$db->query("SELECT * FROM quiz");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countQuizz()
    {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT COUNT(*) as total FROM quiz");
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result["total"];


    }
}
?>