<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;

class UserRepository
{
    private DatabaseConnexion $connexion;

    function __construct()
    {
        $this->connexion = new DatabaseConnexion();
    }
    
    //Returns a User object
    public function getUser(int $userId) : User
    {
        $statement = $this->connexion->getConnexion()->getConnexion()->prepare(
            "SELECT * FROM user WHERE userId = ?;"
        );

        $statement->execute([$userId]);

        $user = new User();

        while ($row = $statement->fetch()) {
            $user->id = $row['userId'];
            $user->name = $row['name'];
            $user->surname = $row['surname'];
            $user->pseudo = $row['pseudo'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->creationDate = $row['creationDate'];
            $user->userFunction = $row['userFunction'];
            $user->isValid = $row['isValid'];
        }

        return $user;
    }

    //Returns a user object by it's email address
    public function getUserByMail(string $userEmail) : User
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE email = ?;"
        );

        $statement->execute([$userEmail]);

        $user = new User();

        while ($row = $statement->fetch()) {
            $user->id = $row['userId'];
            $user->name = $row['name'];
            $user->surname = $row['surname'];
            $user->pseudo = $row['pseudo'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->creationDate = $row['creationDate'];
            $user->userFunction = $row['userFunction'];
            $user->isValid = $row['isValid'];
        }

        return $user;
    }

    //Returns an array of User objects
    public function getUsers() : array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user ORDER BY creationDate DESC;"
        );

        $statement->execute();

        $users = array();

        while ($row = $statement->fetch()) {
            $user = new User();
            $user->id = $row['userId'];
            $user->name = $row['name'];
            $user->surname = $row['surname'];
            $user->pseudo = $row['pseudo'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->creationDate = $row['creationDate'];
            $user->userFunction = $row['userFunction'];
            $user->isValid = $row['isValid'];
            $users = $user;
        }

        return $users;
    }

    //Creates a new user
    public function createUser(User $user) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare (
            "INSERT INTO user (name, surname, pseudo, email, password, creationDate, userFunction, isValid) 
            VALUES(?, ?, ?, ?, ?, now(), ?, 0);"
        );

        if ($statement->execute([
            htmlspecialchars($user->name), 
            htmlspecialchars($user->surname), 
            htmlspecialchars($user->pseudo), 
            htmlspecialchars($user->email), 
            Password::encrypt(htmlspecialchars($user->password)), 
            $user->userFunction])) 
        {
            return true;
        } else {
            return false;
        }
    }

    //Updates a user
    public function updateUser(User $user) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET(name = ?, surname = ?, pseudo = ?, email = ?, password = ?, userFunction = ?, isValid = ?) 
            WHERE userId = ?;"
        );

        if ($statement->execute([$user->name, $user->surname, $user->email, Password::encrypt($user->password), 
        $user->userFunction, $user->isValid])) {
            return true;
        } else {
            return false;
        }
    }

    //Deletes a user
    public function deleteUser(int $userId) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM user WHERE userId = ?;"
        );

        if ($statement->execute([$userId])) {
            return true;
        } else {
            return false;
        }
    }

    //Checks if an address email or pseudo exists in the database
    //The field parameter contains the database targeted database field name
    public function exists(string $value, string $field) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT " . $field . " FROM user WHERE " . $field . "=?;"
        );

        $statement->execute([$value]);

        if ($statement->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the parameter is max 50 characters long
     * and is not empty
     */
    public function checkNameSurname(string $value) : bool
    {
        if(strlen($value) > 50 || trim($value) === ""){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks the email validity
     */

    public function checkEmail(string $email) : bool
    {
        return preg_match("/(^[a-zA-Z0-9_.]+[@]{1}[a-z0-9]+[\.][a-z]+$)/", $email);
    }
}