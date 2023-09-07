<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\DatabaseConnexion;


class DatabaseConnexionTest extends TestCase
{
    /**
     * Test de connexion à la base de données
     */
    public function testGetConnexion(){
        $database = new DatabaseConnexion();
        
        $this->assertNotNull($database->getConnexion(), "Class Database : erreur de connexion à la base de données");
    }
}