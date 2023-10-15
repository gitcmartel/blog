<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\DatabaseConnexion;


class DatabaseConnexionTest extends TestCase
{
    #region Functions

    /**
     * Test de connexion à la base de données
     */
    public function testGetConnexion()
    {
        $database = new DatabaseConnexion();
        
        $this->assertNotNull($database->getConnexion());
    }

    #endregion
}