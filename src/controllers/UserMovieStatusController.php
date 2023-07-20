<?php

namespace Daw\Mediahub\Controllers;

use Daw\Mediahub\Controllers\Database;
use Daw\Mediahub\Models\UserMovieStatus;

class UserMovieStatusController extends Database
{
    /**
     * Obtener todos los estados
     * 
     * TODO: Fix array type, mientras se puede usar:
     * \/** @var UserMovieStatus $movieGenre *\/
     * 
     * @return array un array con todos los estados de UserMovie
     */
    public static function getAllUserMovieStatus()
    {
        self::connect();
        $query = self::$db->query('SELECT * FROM usermoviestatus')->fetchAll();
        return array_map(function ($element) {
            return new UserMovieStatus($element['status']);
        }, $query);
    }

    public static function getUserMovieStatusByStatus(string $status)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM usermoviestatus WHERE status=?");
        $query->execute([$status]);
        $movieGenre = $query->fetch();
        if ($movieGenre) {
            return new UserMovieStatus($movieGenre['status']);
        }
        return null;
    }
}
