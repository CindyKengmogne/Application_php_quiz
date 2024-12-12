<?php
require_once '../utils/SessionManager.php';
require_once '../utils/CookieManager.php';
SessionManager::startSession();
SessionManager::destroySession();
CookieManager::deleteCookie('email');
CookieManager::deleteCookie('password');
header('Location: login.php');
exit;
?>