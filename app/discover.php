<?php
require('../vendor/autoload.php');

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}

use Daw\Mediahub\Controllers\MovieController;
use Daw\Mediahub\Controllers\MovieGenreController;
use Daw\Mediahub\Models\MovieGenre;
use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

UIBuilder::doctype('light', 'es');
UIBuilder::head('Inicio', '../resources/styles/style.css');

function defaultView()
{
    echo "<div id='toolbar'>";
    UIBuilder::generateSearchUI(MovieGenreController::getAllMovieGenres());
    echo '</div><br>';
    $genres = MovieGenreController::getAllMovieGenres();
    /**
     * @var MovieGenre $genre
     */
    foreach ($genres as $genre) {
        UIBuilder::title($genre->text, 'Ver todos', "./discover.php?genre={$genre->id}");
        UIBuilder::moviesDisplayer(MovieController::getAllMoviesOfGenre($genre, 8));
        echo '<hr>';
    }
}

function genreView(MovieGenre $genre)
{
    echo '<a class="btn btn-dark" href="discover.php">⬅ Volver</a>';
    UIBuilder::title($genre->text);
    UIBuilder::moviesDisplayer(MovieController::getAllMoviesOfGenre($genre, 999), true);
}

function searchView(string $title, string $genre, string $year)
{
    echo "<div id='toolbar'>";
    UIBuilder::generateClearSearchButton('discover.php');
    echo '</div><br>';
    UIBuilder::title('🔎 Resultados de la búsqueda');
    
    //Process search params
    $title = ($title == '') ? null : $title;    
    $genre = ($genre == '') ? null : MovieGenreController::getMovieGenreById((int)$genre);
    $year = ($year == '') ? null : (int)$year;
    
    //Show results
    UIBuilder::moviesDisplayer(MovieController::searchMovies($title, $genre, $year), true);
}

?>

<body class='d-flex flex-nowrap'>
    <?php
    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user'], SidebarOrigins::Descubrir);
    ?>
    <main>
        <?php
        if (isset($_GET['genre'])) {
            genreView(MovieGenreController::getMovieGenreById($_GET['genre']));
        } else if (isset($_GET['searchTitle'])) {
            searchView(
                $_GET['searchTitle'], 
                $_GET['searchGenre'], 
                $_GET['searchFecha']
            );
        } else {
            defaultView();
        }
        ?>
    </main>
</body>
