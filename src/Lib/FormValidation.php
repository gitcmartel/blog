<?php

namespace Application\Lib;

class FormValidation
{
    //region Properties

    private string $surname;
    private string $name;
    private string $email;
    private string $message;
    private array $errors;

    //endregion

    //region Functions

    function __construct(string $surname, string $name, string $email, string $message)
    {
        $this->surname = htmlspecialchars($surname);
        $this->name = htmlspecialchars($name);
        $this->email = htmlspecialchars($email);
        $this->message = htmlspecialchars($message);
        $this->errors = [
            'surname' => '', 
            'name'    => '', 
            'email'   => '', 
            'message' => ''
        ];
    }

    //Controls the form fields values and returns true or false
    public function isValid() : bool
    {
        return ($this->controlLength() && $this->controlEmail() && $this->controlMessage());
    }

    //Controls the fields length
    //Returns true if all the fields are > 0
    private function controlLength() : bool
    {

        foreach($this as $propertyName => $propertyValue) {
            if ($propertyName !== 'errors' && mb_strlen($propertyValue) === 0) {
                $this->errors[$propertyName] = 'Le champ ne peut pas être vide';
                return false;
            }
        }

        return true;
    }

    /**
     * Controls the email format
     */
    private function controlEmail() : bool
    {
        $emailRegExp = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";

        if (preg_match($emailRegExp, $this->email) !== 1) {
            $this->errors['email'] = 'L\'adresse email est incorrecte';
            return false;
        }

        return true;  
    }

    /**
     * Controls the message caracters length
     */
    private function controlMessage() : bool 
    {
        if ((strlen($this->message) > 20 && strlen($this->message) < 500) === false) {
            $this->errors['message'] = 'Le nombre de caractères doit être compris entre 20 et 500';
            return false;
        }

        return true;
    }

    //endregion

    //region Getters and Setters

    function getSurname() : string 
    {
        return $this->surname;
    }

    function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    function getName() : string 
    {
        return $this->name;
    }

    function setName(string $name)
    {
        $this->name = $name;
    }

    function getEmail() : string 
    {
        return $this->email;
    }

    function setEmail(string $email)
    {
        $this->email = $email;
    }

    function getMessage() : string 
    {
        return $this->message;
    }

    function setMessage(string $message)
    {
        $this->message = $message;
    }

    function getErrors() : array
    {
        return $this->errors;
    }
    //endregion
}