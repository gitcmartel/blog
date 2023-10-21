<?php

namespace Application\Models;

use DateTime;


class User extends Table
{
    #region properties

    private string $name;
    private string $surname;
    private string $pseudo;
    private string $email;
    private string $password;
    private string $tokenForgotPassword;
    private string $forgotPasswordDate;
    private string $creationDate;
    private UserFunction $userFunction;
    private bool $isValid;
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

    /**
     * Checks if the token and it's generation date are valid
     */
    function tokenCheckValidity() : bool
    {
        //If we get a valid user then we proceed
        if($this->id !== null){
            //Check if the date is no more than 24h old
            $tokenDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->forgotPasswordDate);

            $dateDifference = $tokenDate->diff(new DateTime());
            $secondsDifference = ($dateDifference->h * 60 * 60) + ($dateDifference->i * 60) + $dateDifference->s;

            if(($secondsDifference) <= 86400){
               return true;
            }
        }
        return false;
    }

    #endregion

    #region Getters and Setters
    /**
     * Getters and setters
     */

    function getName() : string 
    {
        if(isset($this->name)){
            return $this->name;
        } else {
            return '';
        }
    }

    function setName(string $name)
    {
        $this->name = $name;
    }

    function getSurname() : string 
    {
        if(isset($this->surname)){
            return $this->surname;
        } else {
            return '';
        }
    }

    function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    function getPseudo() : string 
    {
        if(isset($this->pseudo)){
            return $this->pseudo;
        } else {
            return '';
        }
    }

    function setPseudo(string $pseudo)
    {
        $this->pseudo = $pseudo;
    }

    function getEmail() : string 
    {
        if(isset($this->email)){
            return $this->email;
        } else {
            return '';
        }
    }

    function setEmail(string $email)
    {
        $this->email = $email;
    }

    function getPassword() : string 
    {
        if(isset($this->password)){
            return $this->password;
        } else {
            return '';
        }
    }

    function setPassword(string $password)
    {
        $this->password = $password;
    }

    function getTokenForgotPassword() : string 
    {
        if(isset($this->tokenForgotPassword)){
            return $this->tokenForgotPassword;
        } else {
            return '';
        }
    }

    function setTokenForgotPassword(string $tokenForgotPassword)
    {
        $this->tokenForgotPassword = $tokenForgotPassword;
    }

    function getForgotPasswordDate() : string 
    {
        if(isset($this->forgotPasswordDate)){
            return $this->forgotPasswordDate;
        } else {
            return '';
        }
    }

    function setForgotPasswordDate(string $forgotPasswordDate)
    {
        $this->forgotPasswordDate = $forgotPasswordDate;
    }

    function getCreationDate() : string 
    {
        if(isset($this->creationDate)){
            return $this->creationDate;
        } else {
            return '';
        }
    }

    function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    function setUserFunction(string $function)
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


    function getUserFunction() : UserFunction
    {
        if(isset($this->userFunction)){
            return $this->userFunction;
        }else{
            return UserFunction::Else;
        }
    }

    function getIsValid() : bool 
    {
        if (isset($this->isValid)){
            return $this->isValid;
        } else {
            return false;
        }
    }

    function setIsValid(bool $isValid) 
    {
        $this->isValid = $isValid;
    }
    #endregion
}

