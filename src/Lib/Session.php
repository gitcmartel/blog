<?php

namespace Application\Lib;

use Application\Models\User;

class Session
{
    #region Functions

    /**
     * Returns the user pseudo If it is registered in the superglobal $_SESSION
     * returns an empty string if not
     * @return string
     */
    public static function getActiveUser(): string
    {
        $activeUser = filter_var(
            isset($_SESSION['activeUser']) === true ? $_SESSION['activeUser'] : '',
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        if ($activeUser !== false) {
            return $activeUser;
        } else {
            return "";
        }
    }

    /**
     * Returns the active user's function
     * @return string
     */
    public static function getActiveUserFunction(): string
    {
        $activeUserFunction = filter_var(
            isset($_SESSION['activeUserFunction']) === true ? $_SESSION['activeUserFunction'] : '',
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        if ($activeUserFunction !== false) {
            return $activeUserFunction;
        } else {
            return "";
        }
    }

    /**
     * Returns the active user's id
     * @return int
     */
    public static function getActiveUserId(): ?int
    {
        $userId = isset($_SESSION['userId']) === true ? filter_var(
            $_SESSION['userId'],
            FILTER_SANITIZE_NUMBER_INT
        ) : null;

        if ($userId !== false) {
            return $userId;
        } else {
            return null;
        }
    }

    /**
     * Set the active user data into the $_SESSION supervariable
     */
    public static function setUser(User $user): void
    {
        $_SESSION['activeUser'] = $user->getPseudo();
        $_SESSION['activeUserFunction'] = $user->getUserFunction()->toString();
        $_SESSION['userId'] = $user->getId();
    }
    #endregion
}
