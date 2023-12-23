<?php

namespace Application\Models;

use DateTime;


class User extends Table
{
    //region properties

    private string $name;
    private string $surname;
    private string $pseudo;
    private string $email;
    private string $password;
    private ?string $tokenForgotPassword;
    private ?string $forgotPasswordDate;
    private string $creationDate;
    private UserFunction $userFunction;
    private bool $isValid;
    //endregion

    //region Functions
    /**
     * Checks if the user is valid and has the proper function
     * @param array $function
     * @return bool
     */
    function checkValidity(array $function): bool
    {
        if ($this->isValid && in_array($this->userFunction->toString(), $function)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the token and it's generation date are valid
     * @return bool
     */
    function tokenCheckValidity(): bool
    {
        //If we get a valid user then we proceed
        if ($this->id !== null) {
            //Check if the date is no more than 24h old
            $tokenDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->forgotPasswordDate);

            $dateDifference = $tokenDate->diff(new DateTime());
            $secondsDifference = ($dateDifference->h * 60 * 60) + ($dateDifference->i * 60) + $dateDifference->s;

            if (($secondsDifference) <= 86400) {
                return true;
            }
        }
        return false;
    }

    //endregion

    //region Getters and Setters
    /**
     * Getter
     * @return string
     */
    function getName(): string
    {
        if (isset($this->name)) {
            return $this->name;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $name
     */
    function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter
     * @return string
     */
    function getSurname(): string
    {
        if (isset($this->surname)) {
            return $this->surname;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $surname
     */
    function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Getter
     * @return string
     */
    function getPseudo(): string
    {
        if (isset($this->pseudo)) {
            return $this->pseudo;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string
     */
    function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Getter
     * @return string
     */
    function getEmail(): string
    {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $email
     */
    function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**Getter
     * @return string
     */
    function getPassword(): string
    {
        if (isset($this->password)) {
            return $this->password;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $password
     */
    function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    function getTokenForgotPassword(): string
    {
        if (isset($this->tokenForgotPassword)) {
            return $this->tokenForgotPassword;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param ?string $tokenForgotPassword
     */
    function setTokenForgotPassword(?string $tokenForgotPassword): void
    {
        $this->tokenForgotPassword = $tokenForgotPassword;
    }

    /**
     * Getter
     * @return string
     */
    function getForgotPasswordDate(): string
    {
        if (isset($this->forgotPasswordDate)) {
            return $this->forgotPasswordDate;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param ?string $forgotPasswordDate
     */
    function setForgotPasswordDate(?string $forgotPasswordDate): void
    {
        $this->forgotPasswordDate = $forgotPasswordDate;
    }

    /**
     * Getter
     * @return string
     */
    function getCreationDate(): string
    {
        if (isset($this->creationDate)) {
            return $this->creationDate;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $creationDate
     */
    function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Setter
     * @param string $function
     */
    function setUserFunction(string $function)
    {
        switch ($function) {
            case 'Administrateur':
                $this->userFunction = UserFunction::Administrateur;
                break;
            case 'Createur':
                $this->userFunction = UserFunction::Createur;
                break;
            case 'Lecteur':
                $this->userFunction = UserFunction::Lecteur;
                break;
        }
    }

    /**
     * Getter
     * @return UserFunction
     */
    function getUserFunction(): UserFunction
    {
        if (isset($this->userFunction)) {
            return $this->userFunction;
        } else {
            return UserFunction::Autre;
        }
    }

    /**
     * Getter
     * @return bool
     */
    function getIsValid(): bool
    {
        if (isset($this->isValid)) {
            return $this->isValid;
        } else {
            return false;
        }
    }

    /**
     * Setter
     * @param bool $isValid
     */
    function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }
    //endregion
}
