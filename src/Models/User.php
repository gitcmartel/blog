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
    public string $forgotPasswordDate;
    public string $creationDate;
    public string $userFunction;
    public bool $isValid;

    public function constructWithParameters(string $name, string $surname, string $pseudo, string $email, string $password, 
        string $creationDate, string $userFunction, bool $isValid)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->creationDate = $creationDate;
        $this->userFunction = $userFunction;
        $this->isValid = $isValid;
    }

    /**
     * If the user has a function of "createur" or "administrateur"
     * then the function returns true
     */
    public function isCreator() : bool
    {
        if($this->userFunction === "Createur" || $this->userFunction === "Administrateur"){
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the user has a function of "administrateur"
     * then the function returns true
     */
    public function isAdmin() : bool
    {
        if($this->userFunction === "Administrateur"){
            return true;
        } else {
            return false;
        }
    }
}

