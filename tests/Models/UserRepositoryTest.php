<?php

use PHPUnit\Framework\TestCase;
use Application\Models\User;
use Application\Models\UserRepository;
use Application\Tests\Lib\DbConnexionTest;

class UserRepositoryTest extends TestCase 
{
    public function testCreateUser()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        $user = new User();
        $user->name = "Test";
        $user->surname = "testSurname";
        $user->email = "test@orange.fr";
        $user->pseudo = "pseudoTest";
        $user->isValid ="false";
        $user->password = "E3StexKP!C6G5fHi";
        $user->userFunction = "Lecteur";

        //Insert a new user
        $result = $userRepository->createUser($user);

        //Executing test
        $this->assertTrue($result);
    }

    public function testGetUser()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Get the user object of id 0
        $user = $userRepository->getUser(0);

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $user);
    }

    public function testGetUserByMail()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Get the user object
        $user = $userRepository->getUserByMail("test@orange.fr");

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $user);
    }

    public function testGetUsers()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Get the array of user objects
        $users = $userRepository->getUsers(1, 5);

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $users[0]);
    }

    public function testUpdateUser()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        $user = new User();
        $user->id = 0;
        $user->name = "Test1";
        $user->surname = "testSurname";
        $user->email = "test@orange.fr";
        $user->pseudo = "pseudoTest";
        $user->isValid ="false";
        $user->userFunction = "Lecteur";
        $user->password = "E3StexKP!C6G5fHi";

        //Update the user row
        $result = $userRepository->updateUser($user);

        //Executing test
        $this->assertTrue($result);
    }


    public function testExists()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Test if the email is present
        $result = $userRepository->exists('test@orange.fr', 'email');

        //Executing test
        $this->assertTrue($result);
    }

    public function testCheckNameSurnameTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a non empty string < 50 characters
        $result = $userRepository->checkNameSurname('Christophe');

        //Executing test
        $this->assertTrue($result);
    }


    public function testCheckNameSurnameEmptyStringFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an empty string
        $result = $userRepository->checkNameSurname(' ');

        //Executing test
        $this->assertFalse($result);
    }

    public function testCheckNameSurnameLengthFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a more than 50 characters string
        $result = $userRepository->checkNameSurname('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

        //Executing test
        $this->assertFalse($result);
    }


    public function testDeleteUser()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Deleting a user row
        $result = $userRepository->deleteUser(0);

        //Executing test
        $this->assertTrue($result);
    }
}