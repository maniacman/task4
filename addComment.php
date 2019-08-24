<?php
session_start();
if ($_SESSION['auth'] != 'true')
{
	$_SESSION['do_auth'] = 'true';
	header('Location: index.php');
	exit;
}

$comment = htmlspecialchars($_POST['comment']);

if ($comment == '')
{
	$_SESSION['emptyComment'] = 'true';
	header('Location: index.php');
	exit;
}

$login = $_SESSION['login'];
$user_id = $_SESSION['user_id'];

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

$statement = $pdo->prepare("INSERT INTO `comments` (login, comment, access, user_id) VALUES(:login, :comment, :access, :user_id)");
$values = ['login' => $login, 'comment' => $comment, 'access' => 'allowed', 'user_id' => $user_id];
$statement->execute($values);
$_SESSION['addedComment'] = 'true';
header('Location: index.php');
exit;

