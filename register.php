<?php
session_start();
if ($_SESSION['error_registration'] = 'true')
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
    if (count($_SESSION['error_password']) >0)
    {
        $error_password = $_SESSION['error_password'][0];
        $_SESSION['error_password'] = [];
    }
    if (count($_SESSION['error_password_confirmation']) >0)
    {
        $error_password_confirmation = $_SESSION['error_password_confirmation'][0];
        $_SESSION['error_password_confirmation'] = [];
    }
    $_SESSION['error_registration'] = 'false';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
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
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Register</div>

                            <div class="card-body">
                                <form method="POST" action="insert.php">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control<?php if ($error_login) echo ' @error(\'name\') is-invalid @enderror';?>" name="login" autofocus>

                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo $error_login;?></strong>
                                            </span>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control<?php if ($error_email) echo ' @error(\'email\') is-invalid @enderror';?>" name="email" >
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo $error_email;?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control<?php if ($error_password) echo ' @error(\'password\') is-invalid @enderror';?>" name="password"  autocomplete="new-password">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo $error_password;?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control<?php if ($error_password_confirmation) echo ' @error(\'password_confirm\') is-invalid @enderror';?>" name="password_confirmation"  autocomplete="new-password">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo $error_password_confirmation;?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                Register
                                            </button>
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
