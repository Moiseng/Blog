<?php

namespace App;

use PDO;

final class BddConnection
{

    public static function getPDO(): PDO
    {
        return new PDO("mysql:dbname=blog_moise;host=127.0.0.1", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
