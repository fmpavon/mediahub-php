<?php

namespace Daw\Mediahub\Controllers;

use DateTime;
use Daw\Mediahub\Controllers\Database;
use Daw\Mediahub\Models\Movie;
use Daw\Mediahub\Controllers\MovieGenreController;
use Daw\Mediahub\Models\MovieGenre;
use Daw\Mediahub\Models\UserMovie;
use Daw\Mediahub\Models\Usuario;

class MovieController extends Database
{
    /**
     * Obtener todas las películas
     * \/** @var Movie $movie *\/
     * 
     * @return array un array con todas las peliculas
     */
    public static function getAllMovies(int $limit = 10)
    {
        self::connect();
        $query = self::$db->query("SELECT * FROM movie ORDER BY RAND() LIMIT {$limit}")->fetchAll();
        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $query);
    }

    /**
     * Obtener peliculas mas populares
     * \/** @var Movie $movie *\/
     * 
     * @return array
     */
    public static function getPopularMovies()
    {
        self::connect();
        $query = self::$db->query("SELECT
        movie.*,
        COUNT(usermovie.movieId) AS popularity,
        movie_genre.text
      FROM
        movie
      JOIN
        usermovie ON movie.id = usermovie.movieId
      JOIN
        movie_genre ON movie.genre = movie_genre.id
      GROUP BY
        movie.id
      ORDER BY
        popularity DESC
      LIMIT 5;")->fetchAll();
        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $query);
    }

    /**
     * Obtener peliculas recomendadas para un usuario
     * \/** @var Movie $movie *\/
     * 
     * @return array un array con todas las peliculas
     */
    public static function getRecommendedMovies(Usuario $user)
    {
        self::connect();
        $query = self::$db->query("SELECT
        movie.id,
        movie.title,
        movie.releaseDate,
        movie.genre,
        movie.plot
      FROM
        movie
      WHERE
        movie.genre IN (
          SELECT
            movie.genre
          FROM
            movie
          JOIN
            usermovie ON movie.id = usermovie.movieId
          WHERE
            usermovie.userId = {$user->id}
        )
        AND movie.id NOT IN (
          SELECT
            usermovie.movieId
          FROM
            usermovie
          WHERE
            usermovie.userId = {$user->id}
        )
      ORDER BY
        movie.releaseDate DESC;")->fetchAll();
        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $query);
    }

    public static function getAllMoviesOfGenre(MovieGenre $genre, int $limit = 10)
    {
        self::connect();
        $query = self::$db->query("SELECT * FROM movie WHERE genre = {$genre->id} ORDER BY RAND() LIMIT {$limit}")->fetchAll();
        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $query);
    }

    public static function getMovieById(int $id)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM movie WHERE id=?");
        $query->execute([$id]);
        $element = $query->fetch();
        if ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }
        return null;
    }

    public static function searchMovies(string $title = null, MovieGenre $genre = null, int $year = null)
    {
        self::connect();
        $query = "SELECT * FROM movie WHERE 1=1";
        $params = [];

        if ($title !== null) {
            $query .= " AND title LIKE ?";
            $params[] = '%' . $title . '%';
        }

        if ($genre !== null) {
            $query .= " AND genre = ?";
            $params[] = $genre->id;
        }

        if ($year !== null) {
            $query .= " AND YEAR(releaseDate) = ?";
            $params[] = $year;
        }

        $statement = self::$db->prepare($query);
        $statement->execute($params);
        $results = $statement->fetchAll();

        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $results);
    }

    public static function getMoviesFromUserMovies(array $usermovies)
    {
        self::connect();
        $query = "SELECT * FROM movie WHERE id=-1";
        $params = [];
        /**
         * @var UserMovie $usermovie
         */
        foreach ($usermovies as $usermovie) {
            $query .= " OR id=?";
            $params[] = $usermovie->movie->id;
        }

        $statement = self::$db->prepare($query);
        $statement->execute($params);
        $results = $statement->fetchAll();

        return array_map(function ($element) {
            return new Movie(
                $element['id'],
                $element['title'],
                new DateTime($element['releaseDate']),
                MovieGenreController::getMovieGenreById($element['genre']),
                $element['plot'] ?? ''
            );
        }, $results);
    }
}
