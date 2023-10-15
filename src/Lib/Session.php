<?php

namespace Application\Lib;

class Session
{
    #region Functions

    /**
     * Returns the user pseudo If it is registered in the superglobal $_SESSION
     * returns an empty string if not
     */
    public static function getActiveUser() : string
    {
        if (isset($_SESSION['activeUser'])){
            return $_SESSION['activeUser'];
        } else {
            return "";
        }
    }

    public static function getActiveUserFunction() : string
    {
        if (isset($_SESSION['activeUserFunction'])){
            return $_SESSION['activeUserFunction'];
        } else {
            return "";
        }
    }

    public static function getActiveUserId() : int
    {
        if (isset($_SESSION['userId'])){
            return $_SESSION['userId'];
        } else {
            return null;
        }
    }

    #endregion
}