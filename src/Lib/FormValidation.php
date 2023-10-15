<?php

namespace Application\Lib;

class FormValidation
{
    #region Properties
    public string $surname;
    public string $name;
    public string $email;
    public string $message;

    #endregion

    #region Functions
    function __construct(string $surname, string $name, string $email, string $message)
    {
        $this->surname = htmlspecialchars($surname);
        $this->name = htmlspecialchars($name);
        $this->email = htmlspecialchars($email);
        $this->message = htmlspecialchars($message);
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

        foreach($this as $property) {
            if (mb_strlen($property) === 0) {
                return false;
            }
        }

        return true;
    }

    private function controlEmail() : bool
    {
        $emailRegExp = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
        return preg_match($emailRegExp, $this->email) === 1;
    }

    private function controlMessage() : bool 
    {
        return !(strlen($this->message) < 50 || strlen($this->message) > 500);
    }
    #endregion
}