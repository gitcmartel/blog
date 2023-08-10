<?php

namespace Application\Models;

use DateTime;

class User
{
    public int $id;
    public string $name;
    public string $surname;
    public string $pseudo;
    public string $email;
    public string $password;
    public string $tokenForgotPassword;
    public DateTime $forgotPasswordDate;
    public DateTime $creationDate;
    public string $userFunction;
    public bool $isValid;

    public function constructWhithParameters(string $name, string $surname, string $pseudo, string $email, string $password, 
        DateTime $creationDate, string $userFunction, bool $isValid)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->creaionDate = $creationDate;
        $this->userFunction = $userFunction;
        $this->isValid = $isValid;
    }

    /**
     * If the user has a function of "createur" or "administrateur"
     * then the function returns true
     */
    public function isCreator() : bool
    {
        if($this->userFunction === "Createur" || "administrateur"){
            return true;
        } else {
            return false;
        }
    }
}

