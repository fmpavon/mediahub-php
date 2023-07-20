<?php
require('../vendor/autoload.php');

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}

use Daw\Mediahub\Controllers\MovieController;
use Daw\Mediahub\Controllers\UserMovieController;
use Daw\Mediahub\Controllers\UserMovieStatusController;
use Daw\Mediahub\Models\UserMovieStatus;
use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

UIBuilder::doctype('light', 'es');
UIBuilder::head('Biblioteca', '../resources/styles/style.css');

?>

<body class='d-flex flex-nowrap'>
    <?php
    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user'], SidebarOrigins::Biblioteca);
    ?>
    <main>
        <?php
        echo "<div id='toolbar'>
        <a href='library.php' class='btn btn-dark'>🎞 Sin filtro</a>
        ";
        /**
         * @var UserMovieStatus $status
         */
        foreach (UserMovieStatusController::getAllUserMovieStatus() as $status) {
            switch ($status->status) {
                case 'Visto':
                    echo "<a href='library.php?filter=Visto' class='btn btn-dark'>✅ Visto</a>";
                    break;

                case 'Pendiente':
                    echo "<a href='library.php?filter=Pendiente' class='btn btn-dark'>🗓 Pendiente</a>";
                    break;

                case 'Abandonado':
                    echo "<a href='library.php?filter=Abandonado' class='btn btn-dark'>👎Abandonado</a>";
                    break;

                default:
                    echo "<a href='library.php?filter={$status->status}' class='btn btn-dark'>{$status->status}</a>";
                    break;
            }
        }

        echo '</div><br>';

        //Mostramos según filtro o sin filtro

        if (!isset($_GET['filter'])) {
            UIBuilder::title('🎞 Toda tu biblioteca');
            UIBuilder::moviesDisplayer(
                MovieController::getMoviesFromUserMovies(
                    UserMovieController::getAllUserMoviesFromUser(
                        $_SESSION['user']
                    )
                    ),
                    true
            );
        } else {
            switch ($_GET['filter']) {
                case 'Visto':
                    UIBuilder::title('✅ Visto');
                    break;

                case 'Pendiente':
                    UIBuilder::title('🗓 Pendiente');
                    break;

                case 'Abandonado':
                    UIBuilder::title('👎Abandonado');
                    break;

                default:
                    UIBuilder::title($status->status);
                    break;
            }
            UIBuilder::moviesDisplayer(
                MovieController::getMoviesFromUserMovies(
                    UserMovieController::getAllUserMoviesFromUserByStatus(
                        $_SESSION['user'],
                        UserMovieStatusController::getUserMovieStatusByStatus($_GET['filter'])
                    )
                    ),
                    true
            );
        }
        ?>
    </main>
</body>
