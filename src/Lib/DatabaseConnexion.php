<?php

namespace Application\Lib;

use PDO;

class DatabaseConnexion
{
    private static ?PDO $connexion = null;

    protected function construct() : PDO
    {
        return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
    }

    public final function getConnexion() : PDO
    {
        if(static::$connexion === null) {
            static::$connexion = static::construct();
        }
        return static::$connexion;
    }
}