<?php

namespace Daw\Mediahub\Controllers;

use Daw\Mediahub\Controllers\Database;
use Daw\Mediahub\Models\MovieGenre;

class MovieGenreController extends Database
{
    /**
     * Obtener todos los generos
     * 
     * TODO: Fix array type, mientras se puede usar:
     * \/** @var MovieGenre $movieGenre *\/
     * 
     * @return array un array con todos los usuarios
     */
    public static function getAllMovieGenres()
    {
        self::connect();
        $query = self::$db->query('SELECT * FROM movie_genre')->fetchAll();
        return array_map(function ($element) {
            return new MovieGenre($element['id'], $element['text']);
        }, $query);
    }

    public static function getMovieGenreById(int $id)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM movie_genre WHERE id=?");
        $query->execute([$id]);
        $movieGenre = $query->fetch();
        if ($movieGenre) {
            return new MovieGenre($movieGenre['id'], $movieGenre['text']);
        }
        return null;
    }
}
