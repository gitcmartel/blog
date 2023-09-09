<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;
use DateTime;
use PDO;

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
            $user->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
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
            $user->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
            $user->userFunction = $row['userFunction'];
            $user->isValid = $row['isValid'];
        }

        return $user;
    }

    /**
     * Returns an array of user objects
     * If the $pageNumber parameter is set to 0, the function will return all users
     * If the $pageNumber parameter is different than 0, the function will return the corresponding users
     * The $numberOfUsersPerPage determins the number of users to return
     */
    public function getUsers(string $pageNumber, int $numberOfUsersPerPage) : array
    {
        $offset = (($pageNumber - 1) * $numberOfUsersPerPage) >=0 ? (($pageNumber - 1) * $numberOfUsersPerPage) : 0;

        if($pageNumber !== 0 && $numberOfUsersPerPage !== 0){
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM user ORDER BY creationDate DESC LIMIT :numberOfUsersPerPage OFFSET :offset;"
            );

            $statement->bindValue(':numberOfUsersPerPage', $numberOfUsersPerPage, PDO::PARAM_INT);
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

            $statement->execute();

        } else { //We return all users
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM user ORDER BY creationDate DESC;"
            );
    
            $statement->execute();
        }
 
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
            $users[] = $user;
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
            Password::encrypt($user->password), 
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
            "UPDATE user SET name = ?, surname = ?, pseudo = ?, email = ?, password = ?, userFunction = ?, isValid = ? 
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

    /**
     * Updates the tokenForgotPassword and forgotPasswordDate fields
     */
    public function updateToken(int $userId, string $token) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET tokenForgotPassword = ?, forgotPasswordDate = now() 
            WHERE userId = ?;"
        );

        if ($statement->execute([$token, $userId])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks the token validity and returns the corresponding user
     */
     public function isTokenValid(string $token, string $email) : User
     {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE tokenForgotPassword = ? and email = ?;"
        );

        $statement->execute([$token, $email]);

        $user = new User();

        while ($row = $statement->fetch()) {
            $user->id = $row['userId'];
            $user->name = $row['name'];
            $user->surname = $row['surname'];
            $user->pseudo = $row['pseudo'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->tokenForgotPassword = $row['tokenForgotPassword'];
            $user->forgotPasswordDate = $row['creationDate'] !== null ? $row['forgotPasswordDate'] : '';
            $user->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
            $user->userFunction = $row['userFunction'];
            $user->isValid = $row['isValid'];
        }

        return $user;
     }

     public function changePassword(int $userId, string $password)
     {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET tokenForgotPassword = '', forgotPasswordDate = null, password = ? 
            WHERE userId = ?;"
        );

        if ($statement->execute([Password::encrypt($password), $userId])) {
            return true;
        } else {
            return false;
        }
     }

    /**
     * Get the number of records by page
     * The $numberOfUsersPerPage parameter contains the number of posts per page
     */
    public function getTotalPageNumber(int $numberOfUsersPerPage) : int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(userId) AS TotalUsers FROM user;"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalUsers'] / $numberOfUsersPerPage, 2));
    }
}