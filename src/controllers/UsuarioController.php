<?php

namespace Daw\Mediahub\Controllers;

use Daw\Mediahub\Controllers\Database;
use Daw\Mediahub\Models\Usuario;

class UsuarioController extends Database
{
    /**
     * Obtener todos los usuarios
     * 
     * TODO: Fix array type, mientras se puede usar:
     * \/** @var Usuario $user *\/
     * 
     * @return array un array con todos los usuarios
     */
    public static function getAllUsers()
    {
        self::connect();
        $query = self::$db->query('SELECT * FROM user')->fetchAll();
        return array_map(function ($element) {
            return new Usuario($element['id'], $element['name'], $element['username'], $element['password']);
        }, $query);
    }

    /**
     * Obtener un usuario por su username
     *
     * @param string $username
     * @return Usuario
     */
    public static function getUserByUsername(string $username)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM user WHERE username=?");
        $query->execute([$username]);
        $user = $query->fetch();
        if ($user) {
            return new Usuario($user['id'], $user['name'], $user['username'], $user['password']);
        }
        return null;
    }

    public static function getUserById(int $id)
    {
        self::connect();
        $query = self::$db->prepare("SELECT * FROM user WHERE id=?");
        $query->execute([$id]);
        $user = $query->fetch();
        if ($user) {
            return new Usuario($user['id'], $user['name'], $user['username'], $user['password']);
        }
        return null;
    }

    public static function registerUser(Usuario $user)
    {
        self::connect();
        $query = self::$db->prepare("INSERT INTO user (name, username, password) VALUES (?, ?, ?)");
        return $query->execute([$user->name, $user->username, $user->password]);
    }

    public static function modifyUser(Usuario $user)
    {
        self::connect();
        //UPDATE proyectopeliculas.`user` SET password='manu',username='manu',name='Manu' WHERE id=4;
        $query = self::$db->prepare("UPDATE proyectopeliculas.user SET name=?, username=?, password=? WHERE id=?");
        return $query->execute([$user->name, $user->username, $user->password, $user->id]);
    }
}
