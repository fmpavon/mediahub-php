<?php

namespace Daw\Mediahub;

use Daw\Mediahub\Models\Movie;
use Daw\Mediahub\Models\MovieGenre;
use Daw\Mediahub\Models\Usuario;

enum SidebarOrigins: int
{
    case Undefined = 0;
    case Inicio = 1;
    case Descubrir = 2;
    case Biblioteca = 3;
    case Perfil = 4;
}

enum AlertTypes: string
{
    case PRIMARY = 'alert-primary';
    case SECONDARY = 'alert-secondary';
    case SUCCESS = 'alert-success';
    case DANGER = 'alert-danger';
    case WARNING = 'alert-warning';
    case INFO = 'alert-info';
    case LIGHT = 'alert-light';
    case DARK = 'alert-dark';
}

class UIBuilder
{
    public static function doctype($theme, $lang)
    {
        echo "<!DOCTYPE html>
    <html data-bs-theme='{$theme}' lang='{$lang}'>";
    }

    public static function head($title, $cssPath)
    {
        echo "<head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>{$title}</title>

        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ' crossorigin='anonymous'>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe' crossorigin='anonymous'
            defer></script>
            <link rel='stylesheet' href='{$cssPath}'>
    </head>";
    }

    public static function svgIcons()
    {
        echo "<svg xmlns='http://www.w3.org/2000/svg' style='display: none;'>
        <symbol id='iconHome' viewBox='0 0 16 16'>
            <path
                d='M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z'>
            </path>
        </symbol>
        <symbol id='iconDiscover' viewBox='0 0 16 16'>
            <path
                d='M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z' />
        </symbol>
        <symbol id='iconLibrary' viewBox='0 0 16 16'>
            <path
                d='M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z'>
            </path>
        </symbol>
        <symbol id='iconProfile' viewBox='0 0 16 16'>
            <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'></path>
            <path fill-rule='evenodd'
                d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z'>
            </path>
        </symbol>
    </svg>";
    }

    //TODO: Implementar nombre de usuario, icono de usuario, rol de usuario
    public static function sidebar(Usuario $user, $origin = null)
    {
        $buttonsState = array(
            "link-body-emphasis", // SidebarOrigins::Undefined
            "link-body-emphasis", // SidebarOrigins::Inicio
            "link-body-emphasis", // SidebarOrigins::Descubrir
            "link-body-emphasis", // SidebarOrigins::Biblioteca
            "link-body-emphasis"  // SidebarOrigins::Perfil
        );
        if (!is_null($origin)) {
            $buttonsState[$origin->value] = 'active';
        }

        //Sidebar para pantallas grandes
        echo "<nav class='d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary navDesktop'>
            <a href='#' class='d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none'>
                <img src='../resources/logo.svg' class='navbar-logo pe-none me-2' width='40' height='32'>
                <span class='fs-4'>MediaHub</span>
            </a>
            <hr>
            <ul class='nav nav-pills flex-column mb-auto'>
                <li class='nav-item'>
                    <a href='home.php' class='nav-link " . $buttonsState[SidebarOrigins::Inicio->value] . "' aria-current='page'>
                        <svg class='navbar-logo pe-none me-2' width='16' height='16'>
                            <use xlink:href='#iconHome'></use>
                        </svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href='discover.php' class='nav-link " . $buttonsState[SidebarOrigins::Descubrir->value] . "'>
                        <svg class='navbar-logo pe-none me-2' width='16' height='16'>
                            <use xlink:href='#iconDiscover'></use>
                        </svg>
                        Descubrir
                    </a>
                </li>
                <li>
                    <a href='library.php' class='nav-link " . $buttonsState[SidebarOrigins::Biblioteca->value] . "'>
                        <svg class='navbar-logo pe-none me-2' width='16' height='16'>
                            <use xlink:href='#iconLibrary'></use>
                        </svg>
                        Mi biblioteca
                    </a>
                </li>
                <li>
                    <a href='profile.php' class='nav-link " . $buttonsState[SidebarOrigins::Perfil->value] . "'>
                        <svg class='navbar-logo pe-none me-2' width='16' height='16'>
                            <use xlink:href='#iconProfile'></use>
                        </svg>
                        Mi perfil
                    </a>
                </li>
            </ul>
            <hr>
            <div class='dropdown'>
                <a href='#' class='d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle'
                    data-bs-toggle='dropdown' aria-expanded='false'>
                    <img src='../resources/images/profilePicture/{$user->id}.png' alt='' class='rounded-circle me-2' width='32' height='32'>
                    <strong><small>{$user->name}</small></strong>
                    <!-- <span class='badge bg-secondary m-1'>Usuario</span> -->
                    <!-- <span class='badge bg-primary m-1'>Editor</span> -->
                    <!-- <span class='badge bg-warning m-1'>Administrador</span> -->
                </a>
                <ul class='dropdown-menu text-small shadow'>
                    <li><a class='dropdown-item' href='settings.php'>Ajustes</a></li>
                    <li>
                        <hr class='dropdown-divider'>
                    </li>
                    <li><a class='dropdown-item' href='home.php?logout=1'>Cerrar sesión</a></li>
                </ul>
            </div>
        </nav>";

        //Sidebar para pantallas pequeñas
        foreach ($buttonsState as $buttonStat) {
            if ($buttonStat == 'link-body-emphasis') {
                $buttonStat = '';
            }
        }
        echo "<nav class='d-flex flex-column flex-shrink-0 bg-body-tertiary navMobile' style='width: 4.5rem;'>
            <a href='/' class='d-block p-3 link-body-emphasis text-decoration-none' data-bs-toggle='tooltip'
                data-bs-placement='right' data-bs-original-title='Icon-only'>
                <img src='../resources/logo.svg' class='navbar-logo pe-none me-2' width='40' height='32'>
                <span class='visually-hidden'>Icon-only</span>
            </a>
            <ul class='nav nav-pills nav-flush flex-column mb-auto text-center'>
                <li class='nav-item'>
                    <a href='home.php' class='nav-link " . $buttonsState[SidebarOrigins::Inicio->value] . " py-3 border-bottom rounded-0' data-bs-toggle='tooltip'
                    ata-bs-placement='right' aria-label='Home' data-bs-original-title='Home'>
                        <svg class='bi pe-none' width='24' height='24' role='img' aria-label='Home'>
                            <use xlink:href='#iconHome'></use>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href='discover.php' class='nav-link " . $buttonsState[SidebarOrigins::Descubrir->value] . " py-3 border-bottom rounded-0' data-bs-toggle='tooltip'
                        data-bs-placement='right' aria-label='Dashboard' data-bs-original-title='Dashboard'>
                        <svg class='bi pe-none' width='24' height='24' role='img' aria-label='Dashboard'>
                            <use xlink:href='#iconDiscover'></use>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href='library.php' class='nav-link " . $buttonsState[SidebarOrigins::Biblioteca->value] . " py-3 border-bottom rounded-0' data-bs-toggle='tooltip'
                        data-bs-placement='right' aria-label='Orders' data-bs-original-title='Orders'>
                        <svg class='bi pe-none' width='24' height='24' role='img' aria-label='Orders'>
                            <use xlink:href='#iconLibrary'></use>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href='profile.php' class='nav-link " . $buttonsState[SidebarOrigins::Perfil->value] . " py-3 border-bottom rounded-0' data-bs-toggle='tooltip'
                        data-bs-placement='right' aria-label='Products' data-bs-original-title='Products'>
                        <svg class='bi pe-none' width='24' height='24' role='img' aria-label='Products'>
                            <use xlink:href='#iconProfile'></use>
                        </svg>
                    </a>
                </li>
            </ul>
            <div class='dropdown border-top'>
                <a href='#'
                    class='d-flex align-items-center justify-content-center p-3 link-body-emphasis text-decoration-none dropdown-toggle'
                    data-bs-toggle='dropdown' aria-expanded='false'>
                    <img src='../resources/images/profilePicture/{$user->id}.png' alt='mdo' class='rounded-circle' ew21ojzth='' width='24'
                        height='24'>
                </a>
                <ul class='dropdown-menu text-small shadow'>
                    <li class='d-flex flex-column m-auto'>
                        <b class='m-auto'>Francisco</b>
                        <!-- <span class='badge bg-warning m-auto'>Administrador</span> -->
                    </li>
                    <li>
                        <hr class='dropdown-divider'>
                    </li>
                    <li><a class='dropdown-item' href='settings.php'>Ajustes</a></li>
                    <li>
                        <hr class='dropdown-divider'>
                    </li>
                    <li><a class='dropdown-item' href='home.php?logout=1'>Cerrar sesión</a></li>
                </ul>
            </div>
        </nav>

        <div class='navbar-divider'>
    </div>";
    }

    /**
     * Generar una alerta simple
     *
     * @param AlertTypes $type el tipo de alerta
     * @param string $content el texto de la alerta
     * @return string la alerta en HTML
     */
    public static function alert($type, string $content)
    {
        return "<div class='alert {$type}' role='alert'>
        {$content}
      </div>";
    }

    public static function moviesDisplayer(array $movies, $fullMoviesContainer = false)
    {
        $fullMoviesContainer = ($fullMoviesContainer) ? 'fullMoviesContainer' : '';
        echo "<div class='d-flex flex-wrap moviesContainer {$fullMoviesContainer}'>";
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            echo "<a href='movie.php?id={$movie->id}' class='text-decoration-none d-flex flex-column text-light'>
            <img src='../resources/images/moviesMain/{$movie->id}.jpg'>
            <div class='p-1'>
                <p class='m-0 h6'><b>{$movie->title}</b></p>
                <small class='small'>{$movie->releaseDate->format('Y')}</small>
            </div>
        </a>";
        }
        echo "</div>";
    }

    public static function moviesSlideshow(array $movies, string $title = '🤩 Más popular')
    {
        echo "<div id='homeNextContainer' class='p-3 text-bg-secondary rounded-3 w-auto' style='width: 100%;'>
        <h3>{$title}</h3>
        <hr>
        <div id='nextConentCarousel' class='carousel slide'>
            <div class='carousel-inner rounded-3'>";
        $activeText = ' active';
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            echo "<a href='movie.php?id={$movie->id}' class='carousel-item{$activeText}'>
            <img src='../resources/images/moviesBackground/{$movie->id}.jpg' class='d-block w-100'>
            <div class='carousel-caption d-none d-md-block'>
                <h5>{$movie->title}</h5>
                <p>{$movie->releaseDate->format('Y')}</p>
            </div>
        </a>";
            $activeText = '';
        }
        echo "</div>
            <button class='carousel-control-prev' type='button' data-bs-target='#nextConentCarousel'
                data-bs-slide='prev'>
                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Anterior</span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#nextConentCarousel'
                data-bs-slide='next'>
                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Siguiente</span>
            </button>
        </div>
    </div>";
    }

    public static function title(string $title, string $urlTitle = '', string $url = '#')
    {
        echo "<div class='d-flex justify-content-center align-content-center title'>
        <h2 class='text-center'>{$title}</h2>
        <a href='{$url}' class='m-1 text-decoration-none'>{$urlTitle}</a>
        </div>";
    }

    public static function generateSearchUI(array $genres)
    {
        echo "<div id='searchContainer'>
            <button type='button' class='btn btn-light border border-secondary dropdown-toggle' data-bs-toggle='dropdown'
                aria-expanded='false' data-bs-auto-close='outside'>
                🔎 Búsqueda
            </button>
            <form action='' method='get' class='dropdown-menu p-4'>
                <div class='form-floating mb-3'>
                    <input type='text' class='form-control' id='searchTitle' name='searchTitle' placeholder='Título de película'>
                    <label for='searchTitle'>Título</label>
                </div>
                <div class='form-floating mb-3'>
                    <select class='form-select' id='searchGenre' name='searchGenre' aria-label='Género de película'>
                        <option selected></option>";
        /**
         * @var MovieGenre $genre
         */
        foreach ($genres as $genre) {
            echo "<option value='{$genre->id}'>{$genre->text}</option>";
        }
        echo        "</select>
                    <label for='searchGenre'>Género</label>
                </div>
                <div class='form-floating mb-3'>
                    <input class='form-control' type='number' min='1900' max='2023' step='1' id='searchFecha' name='searchFecha'/>
                    <label for='searchFecha'>Año</label>
                </div>
                <button type='submit' class='btn btn-primary w-100'>Buscar</button>
            </form>
        </div>";
    }

    public static function generateClearSearchButton($origin)
    {
        echo "<a href='{$origin}' type='button' class='btn btn-danger'>
            🗑 Limpiar búsqueda
        </a>";
    }
}
