<?php

use Couchbase\Role;

require_once "../utils/DbConnexion.php";
//$datebase=new Database();
//$db=$datebase->getConnection();

class User {

    private static PDO $db;
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $dateInscription;
    
    public function __init__() {
        self::$db = DbConnexion::getConnection();
    }

    public static function create($name, $email, $password) {
        self::$db = DbConnexion::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$db->prepare("INSERT INTO users (name, email, password,role) VALUES (?, ?, ?, 'player')");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    public static function findById($id) {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function findByEmail($email) {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $name, $email, $password) {
        self::$db = DbConnexion::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $hashedPassword, $id]);
    }

    public static function delete($id) {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getAllUsers() {
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createAdmin( $name,  $email,  $password)
    {
        self::$db = DbConnexion::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    public  static function  countUser(){
        self::$db = DbConnexion::getConnection();
        $stmt = self::$db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public  static function  countbyRole($role){
        self::$db = DbConnexion::getConnection();
        $stmt=self::$db->prepare("SELECT COUNT(*)  as total FROM users WHERE role = ?");
        $stmt->execute([$role]);
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }




}
?>