<?php

namespace Application\Lib;

use PDO;

class DatabaseConnexion
{
    //region Properties
    private static ?PDO $connexion = null;
    
    //endregion
    
    //region Functions
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
    //endregion
}