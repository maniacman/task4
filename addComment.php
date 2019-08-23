<?php
session_start();
$login = htmlspecialchars(trim($_POST['login']));
$comment = htmlspecialchars($_POST['comment']);
if ($comment == '')
{
	header('Location: index.php');
	exit;
}
$dateComment = $_SERVER['REQUEST_TIME'];

$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');

$statement = $pdo->prepare("INSERT INTO `comments` (login, comment, date_comment, access) VALUES(:login, :comment, :dateComment, :access)");
$values = ['login' => $login, 'comment' => $comment, 'dateComment' => $dateComment, 'access' => 'allowed'];
$statement->execute($values);
$_SESSION['addedComment'] = 'true';
header('Location: index.php');
exit;

