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
        //return new PDO('mysql:host=localhost;dbname=u135719036_blog;charset=utf8', 'u135719036_adminoccc', '9sj6!@9fBc8rKfhe');
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