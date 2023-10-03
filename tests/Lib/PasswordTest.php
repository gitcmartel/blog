<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\Password;


class PasswordTest extends TestCase
{
    /**
     * Test if the function returns a string
     */
    public function testEncrypt()
    {
        $password = "123Mfjdkfj!?+uridjsf47";
        $encryptedPassword = Password::encrypt($password);

        $this->assertTrue(trim($encryptedPassword) !== "");
    }

    /**
     * Check if the password hash corresponds to the original password
     */
    public function testVerifyTrue()
    {
        $hash = '$2y$10$Zy10d/MISY/qkf9OAh8JleGPMIjau37dk/55WtZnoVhoXZqk.wdy6';
        $password = "6?S4hgmJMGr!PtS@c";
        $result = Password::verify($password, $hash);

        $this->assertTrue($result);
    }

    /**
     * Check if the password hash does not correspond to the original password
     */
    public function testVerifyFalse()
    {
        $hash = '$2y$10$Zy10d/MISY/qkf9OAh8JleGPMIjau37dk/55WtZnoVhoXZqk.wdy6';
        $password = "test";

        $result = Password::verify($password, $hash);
        $this->assertFalse($result);
    }

    /**
     * Check the password validity - Correct password
     */
    public function testCheckPasswordTrue()
    {
        $password = '12Htd@uyt'; //Valid password ()

        $this->assertTrue(Password::checkPassword($password));
    }

    /**
     * Check the password validity - Incorrect password
     */
    public function testCheckPasswordFalse()
    {
        $password = "12Ht"; //Valid password ()

        $this->assertFalse(Password::checkPassword($password));
    }

    /**
     * Check that the function do not returns an empty string
     */
    public function testGenerateToken()
    {
        $token = Password::generateToken();

        $this->assertTrue(($token !== ""));
    }
}