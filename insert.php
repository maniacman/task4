<?php
session_start();

//из массива POST получаю логин, email, и два пароля, слегка подготавливаю их к работе
$login = htmlspecialchars(trim($_POST['login']));
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));
$password_confirmation = htmlspecialchars(trim($_POST['password_confirmation']));

//проверяю логин, email и пароли на соответствие заданным условиям
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

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

//проверяю логин на уникальность
$statement = $pdo->prepare("SELECT * FROM `users` WHERE `login` = :login");
$values = ['login' => $login];
$statement->execute($values);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
if(count($users) > 0)
{
	$error_login[] = 'Этот логин уже используется. Придумайте другой.';
}

//проверяю email на уникалность
$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
$val = ['email' => $email];
$statement->execute($val);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
if(count($users) > 0)
{
	$error_email[] = 'Эта почта уже используется. Авторизуйтесь или введите другую.';
}

//если есть хоть одна ошибка, то сохраняю в сессию данные об ошибках и возвращаю пользователя обратно на форму регистрации, там он получит уведомления о допущенных ошибках
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
//если ошибок не обраружено, то хэширую пароль и сохраняю данные о пользователе в БД, а пользователя отправляю на страницу авторизации
$password = password_hash($password, PASSWORD_DEFAULT);

$statement = $pdo->prepare("INSERT INTO `users` (role, login, email, password, filename) VALUES(:role, :login, :email, :password, :filename)");
$values = ['role' => 'user', 'login' => $login, 'email' => $email, 'password' => $password, 'filename' => 'user.jpg'];
$statement->execute($values);
header('Location: login.php');
exit;