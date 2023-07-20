<?php
require('../vendor/autoload.php');

use Daw\Mediahub\Controllers\MovieController;
use Daw\Mediahub\Controllers\UsuarioController;
use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;
use Daw\Mediahub\Models\Usuario;

session_start();
$name = $_SESSION['user']->name;
$id = $_SESSION['user']->id;
$username = $_SESSION['user']->username;
$password = $_SESSION['user']->password;
if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}
if (isset($_POST['guardarCambiosNombre'])) {
    $newName = $_POST['nombre'];
    /** @var Usuario $modUser */
    $modUser = $_SESSION['user'];
    $modUser->name = $newName;
    UsuarioController::modifyUser($modUser);
    $_SESSION['user'] = UsuarioController::getUserByUsername($modUser->username);
    header("Location: settings.php");
} else if (isset($_POST['guardarCambiosPassword'])) {
    $newPassword = $_POST['password'];
    /** @var Usuario $modUser */
    $modUser = $_SESSION['user'];
    $modUser->password = $newPassword;
    UsuarioController::modifyUser($modUser);
    $_SESSION['user'] = UsuarioController::getUserByUsername($modUser->username);
    header("Location: settings.php");
}

// Directorio donde se guardarán las imágenes
$directorio = './resources/images/perfil/';

// Comprobamos si se ha seleccionado un archivo
if (isset($_FILES['image'])) {
    $archivo = $_FILES['image']['tmp_name'];
    $nombre = $_FILES['image']['name'];
    $ruta = $directorio . $nombre;

    // Movemos el archivo a la carpeta de destino
    if (move_uploaded_file($archivo, $ruta)) {
        echo "La imagen se ha subido correctamente.";
        UsuarioController::updateImgPerfil($nombre, $id);
        $imgPerfil = UsuarioController::getUser($id)->imgPerfil;
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ajustes de usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./settings.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="./app/logo.svg" alt="logo" class="navbar-logo pe-none me-2 mr-2" style="width: 1.5em;">
                <span class="fs-4" style="color:black">MediaHub</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Descubrir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Biblioteca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Perfil</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container-config" style="margin:3%">
        <h1 class="ml-2 mt-2">Configuracion Perfil</h1>
        <div class="mt-2em container-boxTitle">
            <h5>Imagen de perfil</h5>
            <div class="d-flex align-items-center container-imagenPerfil  justify-content-center" style="margin-top:1em;padding:1em">
                <img src="./resources/images/perfil/<?php echo $imgPerfil ?>" alt="" style="width:5em;height:6em;padding:0.5em" class="border border-dark rounded">
                <form action="settings.php" method="post" enctype="multipart/form-data" class="ml-3">
                    <input type="file" name="image" accept="image/*" required>
                    <input type="submit" value="Subir imagen">
                </form>
            </div>
        </div>
        <div class="mt-5 container-ajustes">
            <h5 class="h5-ajustes">Ajustes</h5>
            <div style="width:30%">
                <div class="d-flex align-items-center justify-content-between"><span>Nombre : <?php echo $name ?></span><button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#usuario">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                        </svg>
                    </button></div>
                <!-- TODO: Deshabilitado nombre de usuario por incompatibilidades -->
                <!-- <div class="d-flex align-items-center justify-content-between mt-2"><span>Nombre de usuario: <?php echo $username ?></span><button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#username">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                        </svg>
                    </button></div> -->
                <div class="d-flex align-items-center justify-content-between mt-2"><span>Contraseña: <?php echo $password ?></span><button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                        </svg>
                    </button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Cambiar Nombre
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre Nuevo">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="guardarCambiosNombre">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Cambiar Contraseña
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="text" name="password" id="password" placeholder="Contraseña">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="guardarCambiosPassword">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- TODO: Deshabilitado nombre de usuario por incompatibilidades -->
    <!-- <div class="modal fade" id="username" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Cambiar Nombre de Usuario
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="text" name="username" id="username" placeholder="Nombre de Usuario">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="guardarCambiosNombreUsuario">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <footer class="mt-5 pt-3 pb-3" style="background-color: rgba(0,0,0,.75);">
        <ul>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Preguntas Frecuentes</a></li>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Centro de ayuda</a></li>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Términos de uso</a></li>
        </ul>
        <ul>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Privacidad</a></li>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Preferencias de cookies</a></li>
            <li style="list-style:none"><a href="#" style="text-decoration: none">Información corporativa</a></li>
        </ul>
    </footer>
    </div>
</body>

</html>
