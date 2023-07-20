<?php
require('../vendor/autoload.php');

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php?message=Has+cerrado+sesión.");
}

use Daw\Mediahub\Controllers\MovieController;
use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

UIBuilder::doctype('light', 'es');
UIBuilder::head('Inicio', '../resources/styles/style.css');

?>

<body class='d-flex flex-nowrap'>
    <?php
    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user'], SidebarOrigins::Inicio);
    ?>
    <main>
        <?php
        $peliculasPopulares = MovieController::getPopularMovies();
        if (sizeof($peliculasPopulares) < 2) {
            UIBuilder::moviesSlideshow(MovieController::getAllMovies());
        } else {
            UIBuilder::moviesSlideshow($peliculasPopulares);
        }
        echo '<hr>';
        UIBuilder::title('✨ Recomendado para ti');
        $peliculasRecomendadas = MovieController::getRecommendedMovies($_SESSION['user']);
        if (sizeof($peliculasRecomendadas) < 1) {
            echo "<p style='text-align: center'>Para empezar a recibir recomendaciones añade tus primeras películas a la biblioteca desde <a href='discover.php'>aquí</a>.</p>";
        } else {
            UIBuilder::moviesDisplayer($peliculasRecomendadas);
        }
        ?>
    </main>
</body>
