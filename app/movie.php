<?php
require('../vendor/autoload.php');

use Daw\Mediahub\Controllers\MovieController;
use Daw\Mediahub\Controllers\UserMovieController;
use Daw\Mediahub\Controllers\UserMovieStatusController;
use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
    die();
}

if (!isset($_GET['id'])) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
    die();
}

$movie = MovieController::getMovieById((int)$_GET['id']);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'addLibrary':
            UserMovieController::addUserMovie(
                $_SESSION['user'],
                $movie
            );
            break;
        
        case 'deleteLibrary':
            UserMovieController::deleteUserMovie(
                $_SESSION['user'],
                $movie
            );
            break;

        case 'changeLibrary':
            $statusGet = UserMovieStatusController::getUserMovieStatusByStatus($_GET['status']);
            UserMovieController::changeUserMovieStatus(
                $_SESSION['user'],
                $movie,
                $statusGet
            );
            break;
    }
}

UIBuilder::doctype('light', 'es');
UIBuilder::head('Inicio', '../resources/styles/style.css');

?>

<body class='d-flex flex-nowrap'>
    <?php
    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user']);
    ?>
    <main>
        <div class="moviePage">
            <img src="../resources/images/moviesMain/<?= $movie->id ?>.jpg">
            <div>
                <h1><?= $movie->title ?></h1>
                <b><?= $movie->releaseDate->format('Y') ?> · <?= $movie->genre->text ?></b>
                <hr>
                <p><?= $movie->plot ?></p>
                <hr>
                <?php
                $userMovie = UserMovieController::getUserMovieFromUser($_SESSION['user'], $movie);
                if ($userMovie != null) {
                    $statusOptions = UserMovieStatusController::getAllUserMovieStatus();
                    if (($key = array_search($userMovie->status, $statusOptions)) !== false) {
                        unset($statusOptions[$key]);
                    }
                    $statusOptionsString = "";
                    foreach ($statusOptions as $statusOption) {
                        $statusOptionsString = $statusOptionsString .
                            "<li><a class='dropdown-item' href='movie.php?id={$movie->id}&action=changeLibrary&status={$statusOption->status}'>📑 Marcar como " .
                            strtolower($statusOption->status)
                            . "</a></li>";
                    }
                    $statusOptionsString = $statusOptionsString . "<li><a class='dropdown-item' href='movie.php?id={$movie->id}&action=deleteLibrary'>❌ Eliminar de biblioteca</a></li>";
                    echo "<div class='btn-group' role='group' aria-label='Button group with nested dropdown'>
                        <button type='button' class='btn btn-light'>{$userMovie->status->status}</button>

                        <div class='btn-group' role='group'>
                            <button type='button' class='btn btn-secondary dropdown-toggle' data-bs-toggle='dropdown'
                                aria-expanded='false'>
                            </button>
                            <ul class='dropdown-menu'>
                                {$statusOptionsString}
                            </ul>
                        </div>
                    </div>";
                } else {
                    echo "<a href='movie.php?id={$movie->id}&action=addLibrary' type='button' class='btn btn-success'>Añadir a la biblioteca</a>";
                }
                ?>
            </div>
        </div>
    </main>
</body>
