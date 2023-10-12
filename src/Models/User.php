<?php

namespace Application\Models;

use DateTime;


class User
{
    #region properties
    public int $id;
    public string $name;
    public string $surname;
    public string $pseudo;
    public string $email;
    public string $password;
    public string $tokenForgotPassword;
    public string $forgotPasswordDate;
    public string $creationDate;
    public UserFunction $userFunction;
    public bool $isValid;
    #endregion

    #region Constructor
    public function constructWithParameters(string $name, string $surname, string $pseudo, string $email, string $password, 
        string $creationDate, string $userFunction, bool $isValid)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->creationDate = $creationDate;
        $this->setUserFunction($userFunction);
        $this->isValid = $isValid;
    }
    #endregion

    #region Functions
    /**
     * Checks if the user is valid and has the proper function
     */
    function checkValidity(array $function) : bool
    {     
        if($this->isValid && in_array($this->userFunction->toString(), $function)){
            return true;
        } else {
            return false;
        }
    }
    #endregion

    #region Getters and Setters
    /**
     * Getters and setters
     */
    public function setUserFunction(string $function)
    {
        switch ($function){
            case 'Administrateur':
                $this->userFunction = UserFunction::Administrator;
                break;
            case 'Createur':
                $this->userFunction = UserFunction::Creator;
                break;
            case 'Lecteur':
                $this->userFunction = UserFunction::Reader;
                break;
        }
    }


    public function getUserFunction() : UserFunction
    {
        if(isset($this->userFunction)){
            return $this->userFunction;
        }else{
            return UserFunction::Else;
        }
    }
    #endregion
}

