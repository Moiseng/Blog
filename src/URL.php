<?php

namespace App;

use Exception;

class URL
{

    public static function getINT(string $name, ?int $default = null): ?int
    {
        if (!isset($_GET[$name])) {
            return $default;
        }
        if ($_GET[$name] === "0") {
            return 0;
        }
        if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) { // si ce n'est pas un entier
            throw new Exception("Le paramètre $name dans l'url n'est pas un entier");
        }
        return (int)$_GET[$name];
    }

    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {
        $param = self::getINT($name, $default);
        if ($param !== null && $param <= 0) {
            throw new Exception("Le paramètre $name dans l'url n'est pas un entier positif");
        }
        return $param;
    }

}
