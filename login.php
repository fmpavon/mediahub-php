<?php

use Daw\Mediahub\AlertTypes;
use Daw\Mediahub\Controllers\UsuarioController;
use Daw\Mediahub\UIBuilder;

require('./vendor/autoload.php');

if (isset($_POST['login'])) {
  $user = UsuarioController::getUserByUsername($_POST['username']);
  if (password_verify($_POST['password'], $user->password)) {
    session_start();
    $_SESSION['user'] = $user;
    header("Location: ./app/home.php");
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="./resources/styles/login.css">
</head>

<body>
  <header>
    <div href="#" class="m-2">
      <img src="./resources/logo.svg" alt="logo" class="navbar-logo pe-none me-2" style="width: 3em;">
      <span class="fs-4" style="color:white">MediaHub</span>
    </div>
  </header>
  <div class="d-flex align-items-center justify-content-center container-form">
    <form action="" method="post" class="border border-dark p-5 pt-7 pb-7" style="height:38em; background-color: rgba(0,0,0,.75);width:40em;">
      <h1 class="mb-4 text-center text-light">Iniciar sesión</h1>
      <div>
        <?php
        if (isset($_GET['message'])) {
          echo UIBuilder::alert(AlertTypes::SUCCESS->value, $_GET['message']);
        }
        if (isset($_POST['login'])) {
          echo UIBuilder::alert(AlertTypes::DANGER->value, '<b>Error.</b> Credenciales incorrectas.');
        }
        ?>
        <div class="mb-4 mt-5">
          <input type="text" name="username" placeholder="Usuario" class="form-control">
        </div>
        <div class="mb-4">
          <input type="password" name="password" placeholder="Contraseña" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block" name="login">Iniciar sesión</button>
        <p class="text-light text-center m-2">¿No tienes cuenta?<a href="./registro.php"> Registrate</a></p>
        <p id="error"></p>
      </div>
    </form>
  </div>
  <footer style="background-color: rgba(0,0,0,.75);">
    <ul>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Preguntas Frecuentes</a></li>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Centro de ayuda</a></li>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Términos de uso</a></li>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Privacidad</a></li>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Preferencias de cookies</a></li>
      <li style="list-style:none"><a href="#" style="text-decoration: none; color:#737373">Información corporativa</a></li>
    </ul>
  </footer>
</body>

</html>
