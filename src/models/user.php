<?php

require_once("src/lib/database.php");
require_once("src/lib/password.php");

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
}

class UserRepository
{
    private DatabaseConnexion $connexion;

    //Returns a User object
    public function getUser(int $userId) : User
    {
        $statement = $this->connexion->getConnexion()->prepare(
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

        if ($statement->execute([$user->name, $user->surname, $user->pseudo, $user->email, 
        Password::encrypt($user->password), $user->userFunction])) {
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
}