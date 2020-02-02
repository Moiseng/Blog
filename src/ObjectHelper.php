<?php

namespace App;


final class ObjectHelper
{

    public static function hydrate($object, array $data, array $fields): void
    {
        foreach ($fields as $field) {
            /* rajoute un set, devant le nom de chaque champ,
               et met la première lettre en mauscule
             ex " setField "
            */
            $method = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $field)));
            $object->$method($data[$field]);
        }
    }

}