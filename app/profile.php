<?php

use Daw\Mediahub\Controllers\UserMovieController;
use Daw\Mediahub\Models\UserMovie;
use Daw\Mediahub\Models\Usuario;

require('../vendor/autoload.php');

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}

/**
 * @var Usuario $user
 */
$user = $_SESSION['user'];

use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

UIBuilder::doctype('light', 'es');
UIBuilder::head('Inicio', '../resources/styles/style.css');

?>

<body class='d-flex flex-nowrap'>
    <?php
    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user'], SidebarOrigins::Perfil);
    ?>
    <main>
        <?php
        $countLibrary = UserMovieController::countUserMoviesOfUser($user);
        echo "<div class='d-flex justify-content-evenly flex-wrap'>
            <img src='../resources/images/profilePicture/{$user->id}.png' class='rounded-circle align-self-center mb-2' style='max-width: 25%; max-height: 15%;'>
            <div>
                <br>
                <br>
                <h1>{$user->name}</h1>
                <p class='m-0'><b>Usuario</b></p>
                <p class='ms-3'><small>@{$user->username}</small></p>
                <p class='m-0'><b>Películas en biblioteca</b></p>
                <p class='ms-3'><small>🎞 {$countLibrary}</small></p>
            </div>
        </div>";
        ?>
    </main>
</body>
