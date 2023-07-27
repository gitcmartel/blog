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
    public DateTime $creationDate;
    public string $userFunction;
    public bool $isValid;

    function __construct(string $name, string $surname, string $pseudo, string $email, string $password, 
        DateTime $creationDate, string $userFunction, bool $isValid)
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
}

