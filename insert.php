<?php
session_start();

$login = htmlspecialchars(trim($_POST['login']));
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));
$password_confirmation = htmlspecialchars(trim($_POST['password_confirmation']));

if ($login == '')
{
	$error_login[] = 'Укажите имя';
}

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

if (strlen($password) < 6)
{
	$error_password[] = 'Пароль слишком короткий. Введите не менее 6 символов';
}

if ($password_confirmation == '')
{
	$error_password_confirmation[] = 'Укажите пароль';
}

if ($password != $password_confirmation)
{
	$error_password_confirmation[] = 'Пароли не совпадают';
}

if (count($error_login) >0 || count($error_email) >0 || count($error_password) >0 || count($error_password_confirmation) >0)
{
	$_SESSION['error_login'] = $error_login;
	$_SESSION['error_email'] = $error_email;
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_password_confirmation'] = $error_password_confirmation;
	$_SESSION['error_registration'] = 'true';
	header('Location: register.php');
	exit;
}

$password = password_hash($password, PASSWORD_DEFAULT);

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
$statement = $pdo->prepare("INSERT INTO `users` (role, login, email, password, filename) VALUES(:role, :login, :email, :password, :filename)");
$values = ['role' => 'user', 'login' => $login, 'email' => $email, 'password' => $password, 'filename' => 'user.jpg'];
$statement->execute($values);
$msg[] = 'Логин и пароль успешно сохранены. Авторизуйтесь, пожалуйста.';
$_SESSION['msg'] = $msg;
header('Location: authorization.php');
exit;