<?php

namespace Application\Tests\Lib;

use Application\Lib\DatabaseConnexion;
use PDO;

class DbConnexionTest extends DatabaseConnexion
{
    #region Constructor
    
    public function construct() : PDO
    {
        return new PDO('mysql:host=localhost;dbname=blogtest;charset=utf8', 'root', '');
    }

    #endregion
}