<?php
session_start();
if ($_SESSION['auth'] != 'true')
{
    if (isset($_COOKIE['email']) && isset($_COOKIE['user_password']))
    {
        $_SESSION['email'] = $_COOKIE['email'];
        $_SESSION['user_password'] = $_COOKIE['user_password'];
        $_SESSION['path'] = substr($_SERVER['REQUEST_URI'], 1);
        header('Location: auto-inside.php');
        exit;
    }
}

if ($_SESSION['addedComment'] == 'true')
{
    $addedComment = $_SESSION['addedComment'];
    $_SESSION['addedComment'] = 'false';
}

if ($_SESSION['emptyComment'] == 'true')
{
    $emptyComment = $_SESSION['emptyComment'];
    $_SESSION['emptyComment'] = 'false';
}

if ($_SESSION['do_auth'] == 'true')
{
    $do_auth = $_SESSION['do_auth'];
    $_SESSION['do_auth'] = 'false';
}

function getAllowedComments()
{
    $pdo = new PDO('mysql:host=localhost;dbname=blog;charset=utf8;', 'root', '');
    $comments = $pdo->query("
        SELECT users.login, comments.date_comment, comments.comment 
        FROM comments 
        INNER JOIN users ON comments.user_id = users.id 
        WHERE comments.access = 'allowed' 
        ORDER BY comments.date_comment DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    return $comments;
}
$comments = getAllowedComments();

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

    <title>Comments</title>

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
                            <div class="card-header"><h3>Комментарии</h3>
                            </div>

                            <div class="card-body">
                                <div class="alert alert-success<?php if ($addedComment != 'true') echo ' d-none';?>" role="alert">
                                    Комментарий успешно добавлен
                                </div>

                                <?php foreach ($comments as $key => $comment): ?>
                                    <div class="media">
                                        <img src="img/no-user.jpg" class="mr-3" alt="..." width="64" height="64">
                                        <div class="media-body">
                                            <h5 class="mt-0"><?php echo $comment[login];?></h5> 
                                            <span><small><?php echo getDateComment($comment[date_comment]);?></small></span>
                                            <p>
                                                <?php echo $comment[comment];?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>



                    <div class="card-body">
                        <div class="alert alert-primary<?php if ($do_auth != 'true') echo ' d-none';?>" role="alert">
                            Чтобы оставить комментарий, <a href="login.php">авторизуйтесь</a> или <a href="register.php">зарегистрируйтесь</a>
                        </div>
                    </div>

                    


                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3>
                            </div>

                            <div class="card-body">

                                <form action="addComment.php" method="post">                                    
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Сообщение</label>
                                        <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo $newComment;?></textarea>
                                    </div>

                                    <div class="alert alert-success<?php if ($emptyComment != 'true') echo ' d-none';?>" role="alert">
                                        Введите комментарий
                                    </div>

                                    <button type="submit" class="btn btn-success">Отправить</button>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
