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
        if (isset($_SESSION['activeUser'])) {
            return $_SESSION['activeUser'];
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
        if (isset($_SESSION['activeUserFunction'])) {
            return $_SESSION['activeUserFunction'];
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
        if (isset($_SESSION['userId'])) {
            return $_SESSION['userId'];
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
