<?php
class CookieManager {
    public static function setCookie($name, $value, $expire) {
        setcookie($name, $value, time() + $expire, "/");
    }

    public static function getCookie($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function deleteCookie($name) {
        setcookie($name, '', time() - 3600, "/");
    }
}
?>