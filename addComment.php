<?php
session_start();
//если оставить комментарий пытается неавторизованный пользователь, то его возвращает на главную
//там он получит уведомление что надо авторизоваться или зарегистрироваться
if ($_SESSION['auth'] != 'true')
{
	$_SESSION['do_auth'] = 'true';
	header('Location: index.php');
	exit;
}

//получаю текст комментария из массива POST, экранирую спецсимволы
$comment = htmlspecialchars($_POST['comment']);

//если комментарий пустой, то возвращаю юзера на главную, там он получит уведомление
if ($comment == '')
{
	$_SESSION['emptyComment'] = 'true';
	header('Location: index.php');
	exit;
}

//если ошибок не обнаружено, то сохраняю данные в БД, в сессию сохраняю уведомление для 
//пользователя об успешно добавленном комментарии
$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

$statement = $pdo->prepare("INSERT INTO `comments` (comment, access, user_id) VALUES(:comment, :access, :user_id)");
$values = ['comment' => $comment, 'access' => 'allowed', 'user_id' => $_SESSION['user_id']];
$statement->execute($values);
$_SESSION['addedComment'] = 'true';
header('Location: index.php');
exit;

