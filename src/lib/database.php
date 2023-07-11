<?php

class DatabaseConnexion
{
    private ?PDO $connexion = null;

    public function getConnexion() : PDO
    {
        if($this->connexion === null) {
            $this->connexion = new PDO('mysql:host=locahost;dbname=blog;charset=utf8', 'root', '');
        }
        return $this->connexion;
    }
}