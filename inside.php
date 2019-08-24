<?php
session_start();

//из массива POST получаю email и пароль, слегка подготавливаю их к работе
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));

//проверяю email и пароль на соответствие заданным условиям
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

//если есть хоть одна ошибка, то сохраняю в сессию данные об ошибках и возвращаю пользователя обратно на форму входа, там он получит уведомления о допущенных ошибках
if (count($error_email) >0 || count($error_password) >0)
{
	$_SESSION['error_email'] = $error_email;
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_to_login'] = 'true';
	header('Location: login.php');
	exit;
}

//если ошибок не обнаружено, то по email нахожу пароль в БД и сверяю с пришедшим в массиве POST. Если пароль верный, то сохраняю в сессию данные о пользователе
//пользователь будет перенаправлен на главную
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
	$_SESSION['user_photo'] = $users[0][filename];
	if ($_POST['remember'] == 'on')//если нажат чекбокс, сохраняю данные для авторизации в $_COOKIE
	{
		setcookie('email', $_SESSION['email'], time() + 31536000);
		setcookie('user_password', $_SESSION['user_password'], time() + 31536000);
	}
	else//если чекбокс не нажат, то удаляю данные в $_COOKIE
	{
		setcookie('email', $_SESSION['email'], time() - 31536000);
		setcookie('user_password', $_SESSION['user_password'], time() - 31536000);
	}
	header('Location: index.php');
	exit;
}
else//если пароль не подошел, то сохраняю сообщения об ошибке и возвращаю пользователя на форму входа, там он получит соответствующее уведомление об ошибке.
{
	$error_password[] = 'Пароль указан неверно';
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_to_login'] = 'true';
	header('Location: login.php');
	exit;
}