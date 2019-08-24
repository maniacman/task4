<?php
session_start();
if ($_SESSION['auth'] != 'true')
{
    if (isset($_COOKIE['email']) && isset($_COOKIE['user_password']))
    {
        $_SESSION['email'] = $_COOKIE['email'];
        $_SESSION['user_password'] = $_COOKIE['user_password'];
        $_SESSION['path'] = substr($_SERVER['REQUEST_URI'], 1);
        header('Location: autoInside.php');
        exit;
    }
}

if ($_SESSION['error_update'] = 'true')
{
    if (count($_SESSION['error_login']) >0)
    {
        $error_login = $_SESSION['error_login'][0];
        $_SESSION['error_login'] = [];
    }
    if (count($_SESSION['error_email']) >0)
    {
        $error_email = $_SESSION['error_email'][0];
        $_SESSION['error_email'] = [];
    }    
    $_SESSION['error_update'] = 'false';
}

if ($_SESSION['updatedUser'] == 'true')
{
    $updatedUser = $_SESSION['updatedUser'];
    $_SESSION['updatedUser'] = 'false';
}

if ($_SESSION['updatedPassword'] == 'true')
{
    $updatedPassword = $_SESSION['updatedPassword'];
    $_SESSION['updatedPassword'] = 'false';
}

if (count($_SESSION['error_new_password']) > 0)
{
    $error_new_password = $_SESSION['error_new_password'][0];
    $_SESSION['error_new_password'] = [];
}

if (count($_SESSION['error_password']) > 0)
{
    $error_password = $_SESSION['error_password'][0];
    $_SESSION['error_password'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Profile</title>

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
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                          <div class="alert alert-success<?php if ($updatedUser != 'true') echo ' d-none';?>" role="alert">
                            Профиль успешно обновлен
                        </div>

                        <form action="updateUser.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Name</label>
                                        <input type="text" class="form-control<?php if ($error_login) echo ' @error(\'name\') is-invalid @enderror';?>" name="login" id="name" value="<?php echo $_SESSION['login'];?>">
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo $error_login;?></strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Email</label>
                                        <input type="email" class="form-control<?php if ($error_email) echo ' @error(\'email\') is-invalid @enderror';?>" name="email" id="email" value="<?php echo $_SESSION['email'];?>">
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo $error_email;?></strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Аватар</label>
                                        <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <img src="img/<?php echo $_SESSION['user_photo'];?>" alt="" class="img-fluid">
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-warning">Edit profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top: 20px;">
                <div class="card">
                    <div class="card-header"><h3>Безопасность</h3></div>

                    <div class="card-body">
                        <div class="alert alert-success<?php if ($updatedPassword != 'true') echo ' d-none';?>" role="alert">
                            Пароль успешно обновлен
                        </div>

                        <form action="updatePassword.php" method="post">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="password">Current password</label>
                                        <input type="password" name="password" class="form-control<?php if ($error_password) echo ' @error(\'password\') is-invalid @enderror';?>" id="password">
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo $error_password;?></strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password">New password</label>
                                        <input type="password" name="new_password" class="form-control<?php if ($error_new_password) echo ' @error(\'new_password\') is-invalid @enderror';?>" id="new_password">
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo $error_new_password;?></strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password_confirmation">Password confirmation</label>
                                        <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
                                    </div>

                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </div>
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
