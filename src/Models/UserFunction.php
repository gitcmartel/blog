<?php

namespace Application\Models;

enum UserFunction {
    case Administrateur;
    case Createur;
    case Lecteur;
    case Autre;

    #region Functions

    public function toString() : string
    {
        return match($this){
            self::Administrateur => 'Administrateur', 
            self::Createur => 'Createur', 
            self::Lecteur => 'Lecteur',
            self::Autre => ''
        };
    }

    #endregion
}