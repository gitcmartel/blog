<?php

namespace Application\Models;

use Application\Lib\Password;
use PDO;

class UserRepository extends Repository
{


    #region Functions
    /**
     * Returns a User object
     * @param int $userId user Id
     * @return User User object
     */
    public function getUser(?int $userId): User
    {
        if ($userId === null) {
            return new User();
        }

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE id = :userId;"
        );

        $statement->bindValue("userId", $userId, PDO::PARAM_INT);

        $statement->execute();

        $user = new User();

        while (($row = $statement->fetch()) === true) {
            $user->hydrate($row);
        }

        return $user;
    }


    /**
     * Returns a user object by it's email address
     * @param string $userEmail User email
     * @return User User object
     */
    public function getUserByMail(string $userEmail): User
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE email = :email;"
        );

        $statement->bindValue("email", $userEmail, PDO::PARAM_STR);
        $statement->execute();

        $user = new User();

        while (($row = $statement->fetch()) === true) {
            $user->hydrate($row);
        }

        return $user;
    }


    /**
     * Returns an array of user objects
     * If the $pageNumber parameter is set to 0, the function will return all users
     * If the $pageNumber parameter is different than 0, the function will return the corresponding users
     * The $numberOfUsersPerPage determins the number of users to return
     * 
     * @param string $pageNumber Number of the desired page
     * @param int $numberOfUsersPerPage Number of users displayed in the page
     * @return array of User objects
     */
    public function getUsers(string $pageNumber, int $numberOfUsersPerPage): array
    {
        $offset = (($pageNumber - 1) * $numberOfUsersPerPage) >= 0 ? (($pageNumber - 1) * $numberOfUsersPerPage) : 0;

        if ($pageNumber !== 0 && $numberOfUsersPerPage !== 0) {
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM user ORDER BY creationDate DESC LIMIT :numberOfUsersPerPage OFFSET :offset;"
            );

            $statement->bindValue(':numberOfUsersPerPage', $numberOfUsersPerPage, PDO::PARAM_INT);
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

            $statement->execute();
        // We return all users
        } else { 
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM user ORDER BY creationDate DESC;"
            );

            $statement->execute();
        }

        $users = [];

        while (($row = $statement->fetch()) === true) {
            $user = new User();
            $user->hydrate($row);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Creates a new user
     * @param User $user User object
     * @return bool
     */
    public function createUser(User $user): bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO user (name, surname, pseudo, email, password, creationDate, userFunction, isValid) 
            VALUES(:name, :surname, :pseudo, :email, :password, now(), :userFunction, :isValid);"
        );

        $statement->bindValue("name", htmlspecialchars($user->getName()), PDO::PARAM_STR);
        $statement->bindValue("surname", htmlspecialchars($user->getSurname()), PDO::PARAM_STR);
        $statement->bindValue("pseudo", htmlspecialchars($user->getPseudo()), PDO::PARAM_STR);
        $statement->bindValue("email", htmlspecialchars($user->getEmail()), PDO::PARAM_STR);
        $statement->bindValue("password", htmlspecialchars($user->getPassword()), PDO::PARAM_STR);
        $statement->bindValue("userFunction", htmlspecialchars($user->getUserFunction()->toString()), PDO::PARAM_STR);
        $statement->bindValue("isValid", htmlspecialchars($user->getIsValid()), PDO::PARAM_BOOL);

        return $statement->execute();
    }

    /**
     * Updates a user
     * @param User $user User object
     * @return bool
     */
    public function updateUser(User $user): bool
    {
        $result = false;

        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET name = :name, surname = :surname, pseudo = :pseudo, email = :email, userFunction = :userFunction, isValid = :isValid 
            WHERE id = :userId;"
        );

        $statement->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $statement->bindValue(':surname', $user->getSurname(), PDO::PARAM_STR);
        $statement->bindValue(':pseudo', $user->getPseudo(), PDO::PARAM_STR);
        $statement->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(':userFunction', $user->getUserFunction()->toString(), PDO::PARAM_STR);
        $statement->bindValue(':isValid', $user->getIsValid(), PDO::PARAM_BOOL);
        $statement->bindValue(':userId', $user->getId(), PDO::PARAM_INT);

        $result = $statement->execute();

        //If there is a password we have to change it
        if ($user->getPassword() !== "") {
            $result = $this->changePassword($user, $user->getPassword());
        }

        return $result;
    }

    /**
     * Deletes a user
     * @param int $userId User id
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM user WHERE id = :userId;"
        );

        $statement->bindValue("userId", $userId, PDO::PARAM_INT);
        return $statement->execute();
    }

    /**
     * Checks if an address email or pseudo exists in the database
     * The field parameter contains the database targeted database field name
     * @param string $value Value of the targeted field
     * @param string $field Field name
     * @return bool
     */
    public function exists(string $value, string $field): bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT " . $field . " FROM user WHERE " . $field . "=:fieldValue;"
        );

        $statement->bindValue("fieldValue", $value, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the parameter is max 50 characters long
     * and is not empty
     * @param string $value Name or surname
     * @return bool
     */
    public function checkNameSurname(string $value): bool
    {
        if (strlen($value) > 50 || trim($value) === "") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks the email validity
     * @param string $email Email adress
     * @return bool
     */
    public function checkEmail(string $email): bool
    {
        return preg_match("/(^[a-zA-Z0-9_.]+[@]{1}[a-z0-9]+[\.][a-z]+$)/", $email);
    }

    /**
     * Updates the tokenForgotPassword and forgotPasswordDate fields
     * @param User $user User object
     * @param string $token Token value
     * @return bool
     */
    public function updateToken(User $user, string $token): bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET tokenForgotPassword = :token, forgotPasswordDate = now() 
            WHERE id = :userId;"
        );

        $statement->bindValue("token", $token, PDO::PARAM_STR);
        $statement->bindValue("userId", $user->getId(), PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Checks the token validity and returns the corresponding user
     * @param string $token Token value
     * @param string $email Email adress
     * @return User
     */
    public function getUserFromToken(string $token, string $email): User
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE tokenForgotPassword = :token and email = :email;"
        );

        $statement->bindValue("token", $token, PDO::PARAM_STR);
        $statement->bindValue("email", $email, PDO::PARAM_STR);
        $statement->execute();

        $user = new User();

        while (($row = $statement->fetch()) === true) {
            $user->hydrate($row);
        }

        return $user;
    }

    /**
     * Update the password field
     * @param User $user User object
     * @param string $password Password
     * @return bool
     */
    public function changePassword(User $user, string $password) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET tokenForgotPassword = '', forgotPasswordDate = null, password = :password 
            WHERE id = :userId;"
        );

        $statement->bindValue("userId", $user->getId(), PDO::PARAM_INT);
        $statement->bindValue("password", Password::encrypt($password), PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Get the number of records by page
     * The $numberOfUsersPerPage parameter contains the number of posts per page
     * @param int $numberOfUsersPerPage Number of desired users to display on each page
     * @return int
     */
    public function getTotalPageNumber(int $numberOfUsersPerPage): int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(id) AS TotalUsers FROM user;"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalUsers'] / $numberOfUsersPerPage, 2));
    }

    /**
     * Returns a list of User objects given the searchString parameter
     * If the searchString parameter is found in one of the following fields : name, surname, email
     * @param string $searchString String to search
     * @return array of User objects
     */
    public function searchUsers(string $searchString) : array
    {
        $searchString = htmlspecialchars($searchString); //Escape special characters

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM user WHERE name LIKE '%' :searchString '%' 
            OR surname LIKE '%' :searchString '%' 
            OR email LIKE '%' :searchString '%' 
            ORDER BY creationDate DESC;"

        );

        $statement->bindValue(':searchString', $searchString, PDO::PARAM_STR);

        $statement->execute();

        $users = [];

        while (($row = $statement->fetch()) === true) {
            $user = new User();

            $user->hydrate($row);

            $users[] = $user;
        }
        return $users;
    }

    /**
     * Set the isValid field to true or false
     * @param int $userId User id
     * @param int $value Value to update
     * @return bool
     */
    public function setValidation(int $userId, int $fieldValue): bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE user SET isValid = :value WHERE id = :id;"
        );

        $statement->bindValue(':value', $fieldValue, PDO::PARAM_INT);
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);

        $affectedLines = $statement->execute();

        return ($affectedLines > 0);
    }
    #endregion
}