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
    public static function verify(string $password, string $hash) : bool 
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if the password matches the constraints
     * At least 8 characters long, 
     * 1 numeric character, 
     * 1 uppercase, 
     * 1lowercase, 
     * 1 special character
     */

     public static function checkPassword(string $password) : bool
     {
        return preg_match(
            "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[`!@#$%^&*()_+\-=\[\]{};':\\|,.<>\/?~])(?=.{8,})/", 
            $password
        );
     }

     /**
      * Generates a random token string
      * which will be used for password renewal
      */

      public static function generateToken()
      {
        return bin2hex(random_bytes(100));
      }
}