<?php

use PHPUnit\Framework\TestCase;
use Application\Models\User;
use Application\Models\UserRepository;
use Application\Tests\Lib\DbConnexionTest;

class UserRepositoryTest extends TestCase 
{
    #region Functions

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
        $user = $userRepository->getUser(1);

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
        $user->id = 1;
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

    public function testCheckEmailTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a correct email format
        $result = $userRepository->checkEmail('test@orange.fr');

        //Executing test
        $this->assertTrue($result);
    }

    public function testCheckEmailWithoutAtCharacterFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $result = $userRepository->checkEmail('testorange.fr');

        //Executing test
        $this->assertFalse($result);
    }

    public function testCheckEmailWithoutDotFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $result = $userRepository->checkEmail('test@orangefr');

        //Executing test
        $this->assertFalse($result);
    }

    public function testCheckEmailWithoutExtensionFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $result = $userRepository->checkEmail('test@orange.');

        //Executing test
        $this->assertFalse($result);
    }

    public function testUpdateToken()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $result = $userRepository->updateToken(1, 'be073f8e30f429dedf134acd7ec5e2b7409a82e846a62ba0b9');

        //Executing test
        $this->assertTrue($result);
    }

    public function testIsTokenValid()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $user = $userRepository->isTokenValid('be073f8e30f429dedf134acd7ec5e2b7409a82e846a62ba0b9', 'test@orange.fr');

        //Executing test
        $this->assertTrue($user->tokenForgotPassword === 'be073f8e30f429dedf134acd7ec5e2b7409a82e846a62ba0b9');
    }

    public function testChangePassword()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with an incorrect email format
        $result = $userRepository->changePassword(1, 'E3StexKP$C6G5fHi');

        //Executing test
        $this->assertTrue($result);
    }

    public function testGetTotalPageNumber()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Creation of 2 more users
        $user = new User();
        $user->name = "Test1";
        $user->surname = "testSurname1";
        $user->email = "test1@orange.fr";
        $user->pseudo = "pseudoTest1";
        $user->isValid ="false";
        $user->password = "E3StexKP!C6G5fHi1";
        $user->userFunction = "Lecteur";

        $result = $userRepository->createUser($user);

        //Creation of 2 more users
        $user = new User();
        $user->name = "Test2";
        $user->surname = "testSurname2";
        $user->email = "test2@orange.fr";
        $user->pseudo = "pseudoTest2";
        $user->isValid ="false";
        $user->password = "E3StexKP!C6G5fHi2";
        $user->userFunction = "Lecteur";

        $result = $userRepository->createUser($user);

        //Test must return 1 (3 users per page)
        $result = $userRepository->getTotalPageNumber(3);

        //Executing test
        $this->assertEquals(1, $result);
    }

    public function testSearchUsersByNameTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a present string value in a name field
        $users = $userRepository->searchUsers('Test2');

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $users[0]);
    }

    public function testSearchUsersBySurameTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a present string value in a surname field
        $users = $userRepository->searchUsers('testSurname2');

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $users[0]);
    }

    public function testSearchUsersByEmailTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a present string value in a email field
        $users = $userRepository->searchUsers('test2@orange.fr');

        //Executing test
        $this->assertInstanceOf("Application\\Models\\User", $users[0]);
    }

    public function testSearchUsersFalse()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a non present string value
        $users = $userRepository->searchUsers('nonpresentvalue');

        $result = count($users);

        //Executing test
        $this->assertTrue($result === 0);
    }

    public function testSetValidationTrue()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);
        
        //Test with a userId = 1 and a value = 0
        $result = $userRepository->setValidation(1, 0);

        //Executing test
        $this->assertTrue($result);
    }

    public function testDeleteUser()
    {
        //Preparing test environnement
        $userRepository = new UserRepository(new DbConnexionTest);

        //Deleting a user row
        $result = $userRepository->deleteUser(1);

        //Executing test
        $this->assertTrue($result);
    }

    #endregion
}