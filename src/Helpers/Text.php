<?php

namespace App\Helpers;


class Text
{

    /**
     * Méthode qui permet de récupérer un extrait de paragraphe de notre article
     */
    public static function excerpt(string $content, int $limit = 60)
    {
        if (mb_strlen($content) <= $limit) {
            return $content; // je retourne la chaine de caractère si celle ci est déjà inférieur a la limit
        }
        $lastSpace = mb_strpos($content, " ", $limit);
        return mb_substr($content, 0, $lastSpace) . "...";
    }

}
