<?php
session_start();
$_SESSION = array();
setcookie('email', $_SESSION['email'], time() - 31536000);
setcookie('user_password', $_SESSION['user_password'], time() - 31536000);
setcookie(session_name(), '', time() - 31536000, '/');
session_destroy();
header('Location: index.php');
exit;