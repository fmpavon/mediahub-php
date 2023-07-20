<?php

namespace Daw\Mediahub\Controllers;

use Daw\Mediahub\Models\UserMovie;
use Daw\Mediahub\Models\Movie;
use Daw\Mediahub\Models\Usuario;
use Daw\Mediahub\Models\UserMovieStatus;

class UserMovieController extends Database
{
    /**
     * Obtener todas las UserMovies
     * 
     * TODO: Esto se debería limitar en el futuro
     * TODO: Fix array type, mientras se puede usar:
     * \/** @var UserMovie $usermovie *\/
     * 
     * @return array un array con todos los usermovies
     */
    public static function getAllUserMovies(int $limit = 10)
    {
        self::connect();
        $query = self::$db->query("SELECT * FROM usermovie ORDER BY RAND() LIMIT {$limit}")->fetchAll();
        return array_map(function ($element) {
            return new UserMovie(
                UsuarioController::getUserById($element['userId']),
                MovieController::getMovieById($element['movieId']),
                UserMovieStatusController::getUserMovieStatusByStatus($element['status'])
            );
        }, $query);
    }

    public static function getAllUserMoviesFromUser(Usuario $user)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM usermovie WHERE userId=?");
        $query->execute([$user->id]);

        $results = $query->fetchAll();
        return array_map(function ($element) {
            return new UserMovie(
                UsuarioController::getUserById($element['userId']),
                MovieController::getMovieById($element['movieId']),
                UserMovieStatusController::getUserMovieStatusByStatus($element['status'])
            );
        }, $results);
    }

    public static function getAllUserMoviesFromUserByStatus(Usuario $user, UserMovieStatus $status)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM usermovie WHERE userId=? AND status=?");
        $query->execute([$user->id, $status->status]);

        $results = $query->fetchAll();
        return array_map(function ($element) {
            return new UserMovie(
                UsuarioController::getUserById($element['userId']),
                MovieController::getMovieById($element['movieId']),
                UserMovieStatusController::getUserMovieStatusByStatus($element['status'])
            );
        }, $results);
    }

    public static function getUserMovieFromUser(Usuario $user, Movie $movie)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM usermovie WHERE userId=? AND movieId=?");
        $query->execute([$user->id, $movie->id]);
        $element = $query->fetch();
        if ($element) {
            return new UserMovie(
                UsuarioController::getUserById($element['userId']),
                MovieController::getMovieById($element['movieId']),
                UserMovieStatusController::getUserMovieStatusByStatus($element['status'])
            );
        }
        return null;
    }

    public static function addUserMovie(Usuario $user, Movie $movie, UserMovieStatus $status = null)
    {
        self::connect();

        $statusValue = ($status !== null) ? $status->status : null;

        if ($statusValue === null) {
            $query = self::$db->prepare("INSERT INTO usermovie (userId, movieId) VALUES (?, ?)");
            $query->execute([$user->id, $movie->id]);
        } else {
            $query = self::$db->prepare("INSERT INTO usermovie (userId, movieId, status) VALUES (?, ?, ?)");
            $query->execute([$user->id, $movie->id, $statusValue]);
        }

        $insertedId = self::$db->lastInsertId();

        if ($insertedId) {
            return new UserMovie($user, $movie, UserMovieStatusController::getUserMovieStatusByStatus($statusValue));
        }

        return null;
    }

    public static function deleteUserMovie(Usuario $user, Movie $movie)
    {
        self::connect();

        $query = self::$db->prepare("DELETE FROM usermovie WHERE userId=? AND movieId=?");
        $query->execute([$user->id, $movie->id]);

        $deletedRowCount = $query->rowCount();

        return $deletedRowCount > 0;
    }

    public static function changeUserMovieStatus(Usuario $user, Movie $movie, UserMovieStatus $status)
    {
        self::connect();

        $statusValue = $status->status;

        $query = self::$db->prepare("UPDATE usermovie SET status = ? WHERE userId = ? AND movieId = ?");
        $query->execute([$statusValue, $user->id, $movie->id]);

        $affectedRows = $query->rowCount();

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    public static function countUserMoviesOfUser(Usuario $user)
    {
        self::connect();

        $query = self::$db->prepare("SELECT COUNT(*) FROM usermovie WHERE userId = ?");
        $query->execute([$user->id]);

        $count = $query->fetchColumn();

        return $count;
    }
}
