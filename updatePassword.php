<?php
session_start();

// из массива POST получаю пароли
$password = htmlspecialchars(trim($_POST['password']));
$new_password = htmlspecialchars(trim($_POST['new_password']));
$new_password_confirmation = htmlspecialchars(trim($_POST['new_password_confirmation']));

//проверяю пароли на непустоту и корректность
if ($password == '')
{
	$error_password[] = 'Укажите пароль';
}

if ($new_password == '')
{
	$error_new_password[] = 'Укажите пароль';
}

if (strlen($new_password) < 6)
{
	$error_new_password[] = 'Пароль слишком короткий. Введите не менее 6 символов';
}

if ($new_password != $new_password_confirmation)
{
	$error_new_password[] = 'Пароли не совпадают';
}

//если при проверки обнаружились ошибки, то они сохраняются в сессию и пользователь возвращается на страницу профиля,
//там он получит уведомление о допущенных ошибках
if (count($error_password) >0 || count($error_new_password) >0)
{
	$_SESSION['error_password'] = $error_password;
	$_SESSION['error_new_password'] = $error_new_password;
	header('Location: profile.php');
	exit;
}

//подключаюсь к БД
$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

//проверяю правильность введенного актуального пароля
$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
$values = ['email' => $_SESSION['email']];
$statement->execute($values);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
$hash = $users[0][password];
if((password_verify($password, $hash)))//если пароль верный, то обновляю данные пользователя в БД, и если установлены куки, их тоже обновляю.
{
	$new_password = password_hash($new_password, PASSWORD_DEFAULT);
	$statement = $pdo->prepare("UPDATE `users` SET `password` = :password WHERE `email` = :email");
	$values = ['password' => $new_password, 'email' => $_SESSION['email']];
	$statement->execute($values);
	$_SESSION['user_password'] = $new_password;
	$_SESSION['updatedPassword'] = 'true';
	if ($_COOKIE['user_password'])
	{
		setcookie('user_password', $_SESSION['user_password'], time() + 31536000);
	}
	header('Location: profile.php');
	exit;
}
else//если пароль не подошел, то сохраняю сообщения об ошибке и возвращаю пользователя на форму входа, там он получит соответствующее уведомление об ошибке.
{
	$error_password[] = 'Пароль указан неверно';
	$_SESSION['error_password'] = $error_password;
	header('Location: profile.php');
	exit;
}