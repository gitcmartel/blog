<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

abstract class Repository
{
    #region Properties
    protected readonly DatabaseConnexion $connexion;
    #endregion

    #region Constructor
    public function __construct(){
        $this->connexion = new DatabaseConnexion();
    }

    #endregion
}