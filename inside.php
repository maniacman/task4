<?php
session_start();

$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));

if ($email == '')
{
	$error_email[] = 'Укажите email';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	$error_email[] = 'Электронная почта указана некорректно';
}

if ($password == '')
{
	$error_password[] = 'Укажите пароль';
}

if (count($error_email) >0 || count($error_password) >0)
{
	$_SESSION['error_email'] = $error_email;
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_to_login'] = 'true';
	header('Location: login.php');
	exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
$values = ['email' => $email];
$statement->execute($values);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
$hash = $users[0][password];
if((password_verify($password, $hash)))
{
	$_SESSION['auth'] = 'true';
	$_SESSION['login'] = $users[0][login];
	$_SESSION['role'] = $users[0][role];
	$_SESSION['email'] = $users[0][email];
	$_SESSION['user_id'] = $users[0][id];
	$_SESSION['user_password'] = $users[0][password];
	if ($_POST['remember'] == 'on')
	{
		setcookie('email', $_SESSION['email'], time() + 31536000);
		setcookie('user_password', $_SESSION['user_password'], time() + 31536000);
	}
	else
	{
		setcookie('email', $_SESSION['email'], time() - 31536000);
		setcookie('user_password', $_SESSION['user_password'], time() - 31536000);
	}
	header('Location: index.php');
	exit;
}
else
{
	$error_password[] = 'Пароль указан неверно';
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_to_login'] = 'true';
	header('Location: login.php');
	exit;
}