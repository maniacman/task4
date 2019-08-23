<?php
session_start();

$login = htmlspecialchars(trim($_POST['login']));
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));
$password_confirmation = htmlspecialchars(trim($_POST['password_confirmation']));

$password = password_hash($password, PASSWORD_DEFAULT);

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
$statement = $pdo->prepare("INSERT INTO `users` (role, login, email, password, filename) VALUES(:role, :login, :email, :password, :filename)");
$values = ['role' => 'user', 'login' => $login, 'email' => $email, 'password' => $password, 'filename' => 'user.jpg'];
$statement->execute($values);
$msg[] = 'Логин и пароль успешно сохранены. Авторизуйтесь, пожалуйста.';
$_SESSION['msg'] = $msg;
header('Location: authorization.php');
exit;