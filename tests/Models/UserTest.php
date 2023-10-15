<?php

use PHPUnit\Framework\TestCase;
use Application\Models\User;

class UserTest extends TestCase 
{
    #region Functions

    /**
     * Test if the function returns true with a valid parameter
     */
    public function testIsCreator()
    {
        $user = New User();
        $user->userFunction = "Createur";

        $this->assertTrue($user->isCreator());
    }

    /**
     * Test if the function returns true with a valid parameter
     */
    public function testIsCreatorAdmin()
    {
        $user = New User();
        $user->userFunction = "Administrateur";

        $this->assertTrue($user->isCreator());
    }

    /**
     * Test if the function returns true with a valid parameter
     */
    public function testIsAdmin()
    {
        $user = New User();
        $user->userFunction = "Administrateur";

        $this->assertTrue($user->isAdmin());
    }

    #endregion
}