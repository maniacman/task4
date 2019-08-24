<?php
session_start();
if ($_SESSION['role'] != 'admin')
{
    header('Location: index.php');
    exit;    
}
function getAllComments()
{
    $pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
    $comments = $pdo->query("
        SELECT users.filename, users.login, comments.date_comment, comments.comment, comments.access, comments.id 
        FROM comments 
        INNER JOIN users ON comments.user_id = users.id 
        ORDER BY comments.date_comment DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    return $comments;
}
$comments = getAllComments();

function getDateComment($dateComment)
{
    $date = strtotime($dateComment);
    $date = date("d/m/Y", $date);
    return $date;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Admin</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto<?php if ($_SESSION['auth'] == 'true') echo ' d-none';?>">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    </ul>


                    <div class="dropdown<?php if ($_SESSION['auth'] != 'true') echo ' d-none';?>">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if (isset($_SESSION['login'])) echo $_SESSION['login'];?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="profile.php">Профиль</a>
                            <a class="dropdown-item" href="exit.php">Выход</a>
                        </div>
                    </div>


                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Админ панель</h3></div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Аватар</th>
                                            <th>Имя</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($comments as $key => $comment): ?>
                                            <tr>
                                                <td>
                                                    <img src="img/<?php echo $comment['filename']?>" alt="" class="img-fluid" width="64" height="64">
                                                </td>
                                                <td><?php echo $comment['login']?></td>
                                                <td><?php echo getDateComment($comment[date_comment]);?></td>
                                                <td><?php echo $comment['comment']?></td>
                                                <td>
                                                    <a href="changeAccess.php?id=<?php echo $comment['id']?>&access=<?php echo $comment['access']?>" class="btn btn-warning<?php if ($comment['access'] == 'allowed') echo ' d-none';?>">Разрешить</a>

                                                    <a href="changeAccess.php?id=<?php echo $comment['id']?>&access=<?php echo $comment['access']?>" class="btn btn-success<?php if ($comment['access'] != 'allowed') echo ' d-none';?>">Запретить</a>

                                                    <a href="deleteComment.php?id=<?php echo $comment['id']?>" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
