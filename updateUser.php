<?php
session_start();

// из массива POST получаю логин и email, немного подготовив их
$login = htmlspecialchars(trim($_POST['login']));
$email = htmlspecialchars(trim($_POST['email']));

//проверяю логин и email на непустоту и корректность
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

//если при проверки обнаружились ошибки, то они сохраняются в сессию и пользователь возвращается на страницу профиля,
//там он получит уведомление о допущенных ошибках
if (count($error_login) >0 || count($error_email) >0)
{
	$_SESSION['error_login'] = $error_login;
	$_SESSION['error_email'] = $error_email;
	$_SESSION['error_update'] = 'true';
	header('Location: profile.php');
	exit;
}

//подключаюсь к БД
$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

//если в массиве POST пришел логин, отличный от сохраненного в сессии, то проверяю его на уникальность
if ($login != $_SESSION['login'])
{
	$statement = $pdo->prepare("SELECT * FROM `users` WHERE `login` = :login");
	$values = ['login' => $login];
	$statement->execute($values);
	$users = $statement->fetchAll(PDO::FETCH_ASSOC);
	if(count($users) > 0)
	{
		$error_login[] = 'Этот логин уже используется. Придумайте другой.';
	}
}

//если в массиве POST пришел email, отличный от сохраненного в сессии, то проверяю его на уникальность
if ($email != $_SESSION['email'])
{
	$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
	$values = ['email' => $email];
	$statement->execute($values);
	$users = $statement->fetchAll(PDO::FETCH_ASSOC);
	if(count($users) > 0)
	{
		$error_email[] = 'Эта почта уже используется.';
	}
}

//если новый логин и/или email не прошел проверку на уникальность, то сообщение об ошибке сохраняется в сессию
//и пользователь перенаправляется на страницу профиля, там он увидит соответствующие уведомления
if (count($error_login) >0 || count($error_email) >0)
{
	$_SESSION['error_login'] = $error_login;
	$_SESSION['error_email'] = $error_email;
	$_SESSION['error_update'] = 'true';
	header('Location: profile.php');
	exit;
}

//если пользователем была подгружена новая картика для фото профиля, то сохраняю её, 
//а старое фото удаляю (если это конечно не фото по умолчанию)
if ($_FILES['image']['name'])
{
	$fileToDelite = $_SESSION['user_photo'];
	$name = uploadImage($_FILES['image']);
	if ($fileToDelite != 'user.jpg')
	{
		$path = 'img/' . $fileToDelite;
		unlink($path);
	}
}
else//если новое фото не загружено, то используем старое фото
{
	$name = $_SESSION['user_photo'];
}

//возвращает уникальное имя для загружаемого файла
function uploadImage($image)
{
	$path = 'img';
	$extension = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
	$filename = DFileHelper::getRandomFileName($path, $extension);
	$target = $path . '/' . $filename . '.' . $extension;
	move_uploaded_file($_FILES['image']['tmp_name'], $target);
	$name = $filename . '.' . $extension;
	return $name;
}
//создает имя файла, и если оно уникальное (такого файла нет в папке) то возвращает его.
class DFileHelper
{
	public static function getRandomFileName($path, $extension='')
	{
		$extension = $extension ? '.' . $extension : '';
		$path = $path ? $path . '/' : '';
		do {
			$name = md5(microtime() . rand(0, 9999));
			$file = $path . $name . $extension;
		} while (file_exists($file));
		return $name;
	}
}

//обновляю данные пользователя в БД, и если установленны куки, то и их тоже обновляю.
$statement = $pdo->prepare("UPDATE `users` SET `login` = :newLogin, `email` = :email, `filename` = :filename WHERE `login` = :login");
$values = ['newLogin' => $login, 'email' => $email, 'filename' => $name, 'login' => $_SESSION['login']];
$statement->execute($values);
$_SESSION['login'] = $login;
$_SESSION['email'] = $email;
$_SESSION['user_photo'] = $name;
$_SESSION['updatedUser'] = 'true';
if ($_COOKIE['email'])
{
	setcookie('email', $_SESSION['email'], time() + 31536000);
}
header('Location: profile.php');
exit;