<?php

class SessionManager {
    public static function startSession() {
        session_start();
    }

    public static function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function getSession($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroySession() {
        session_destroy();
    }
}

?>