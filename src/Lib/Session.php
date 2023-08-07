<?php

namespace Application\Lib;

class session
{
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
}