<?php
$id = $_GET['id'];
$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
$query = $pdo->prepare("DELETE FROM `comments` WHERE `id` = :id");
$values = ['id' => $id];
$query->execute($values);
header('Location: admin.php');