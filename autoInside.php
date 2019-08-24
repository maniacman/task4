<?php
session_start();
$email = $_SESSION['email'];
$user_password = $_SESSION['user_password'];
$path = $_SESSION['path'];

$email = htmlspecialchars(trim($email));
$user_password = htmlspecialchars(trim($user_password));

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
$values = ['email' => $email];
$statement->execute($values);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
$hash = $users[0][password];
if(($user_password == $hash))
{
	$_SESSION['auth'] = 'true';
	$_SESSION['login'] = $users[0][login];
	$_SESSION['role'] = $users[0][role];
	$_SESSION['email'] = $users[0][email];
	$_SESSION['user_id'] = $users[0][id];
	$_SESSION['user_password'] = $users[0][password];
	setcookie('email', $_SESSION['email'], time() + 31536000);
	setcookie('user_password', $_SESSION['user_password'], time() + 31536000);
	header('Location: ' . $path);
	exit;
}
else
{
	setcookie('email', $_SESSION['email'], time() - 31536000);
	setcookie('user_password', $_SESSION['user_password'], time() - 31536000);
	header('Location: ' . $path);
	exit;
}