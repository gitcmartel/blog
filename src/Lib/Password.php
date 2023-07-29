<?php

namespace Application\Lib;

class Password 
{
    /**
     * Returns an encrypted password
     */
    public static function encrypt(string $password) : string 
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the password validity
     */
    public static function verify(string $password, string $hash) : string 
    {
        return password_verify($password, $hash);
    }
}