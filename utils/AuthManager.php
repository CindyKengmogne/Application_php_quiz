<?php
require_once "../classes/User.php";

class AuthManager{
    private \User $user;
    private \PDO $db;
    private const session_name='secure_session';
    private $cookie_name='remember_user';

    public function __construct($db){
        $this->db=$db;
        
    }
}
?>