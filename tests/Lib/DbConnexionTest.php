<?php

namespace Application\Tests\Lib;

use Application\Lib\DatabaseConnexion;
use PDO;

class DbConnexionTest extends DatabaseConnexion
{
    public function getConnexion() : PDO
    {
        if($this->connexion === null) {
            $this->connexion = new PDO('mysql:host=localhost;dbname=blogtest;charset=utf8', 'root', '');
        }
        return $this->connexion;
    }
}