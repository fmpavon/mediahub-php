<?php

namespace Daw\Mediahub\Controllers;

use PDO;

class Database
{
    protected static ?PDO $db = null;
    private static $DB_HOST = "localhost";
    private static $DB_NAME = "mediahub";
    private static $DB_USER = "root";
    private static $DB_PASS = "";

    protected static function connect()
    {
        self::$db = new PDO(
            "mysql:host=" . self::$DB_HOST .  ";dbname=" . self::$DB_NAME . ";charset=utf8",
            self::$DB_USER,
            self::$DB_PASS
        );
    }
}
