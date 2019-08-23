<?php
session_start();
$login = htmlspecialchars(trim($_POST['login']));
$comment = htmlspecialchars($_POST['comment']);

if ($login == '' or $comment == '')
{
	if ($login == '')
	{
		$_SESSION['emptyLogin'] = 'true';
	}
	else
	{
		$_SESSION['login'] = $login;
	}

	if ($comment == '')
	{
		$_SESSION['emptyComment'] = 'true';
	}
	else
	{
		$_SESSION['comment'] = $comment;
	}
	header('Location: index.php');
	exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

$statement = $pdo->prepare("INSERT INTO `comments` (login, comment, access) VALUES(:login, :comment, :access)");
$values = ['login' => $login, 'comment' => $comment, 'access' => 'allowed'];
$statement->execute($values);
$_SESSION['addedComment'] = 'true';
header('Location: index.php');
exit;

