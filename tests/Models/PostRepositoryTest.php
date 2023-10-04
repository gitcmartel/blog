<?php

use PHPUnit\Framework\TestCase;
use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\User;
use Application\Models\UserRepository;
use Application\Tests\Lib\DbConnexionTest;

class PostRepositoryTest extends TestCase
{
    public function testCreatePost()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);
        
        //Insert a new user
        $userRepository = new UserRepository(new DbConnexionTest);

        $user = new User();
        $user->id = 1;
        $user->name = "Test";
        $user->surname = "testSurname";
        $user->email = "test@orange.fr";
        $user->pseudo = "pseudoTest";
        $user->isValid ="false";
        $user->password = "E3StexKP!C6G5fHi";
        $user->userFunction = "Lecteur";

        $userRepository->createUser($user);

        //Insert a new Post
        $post = new Post();
        $post->title = "TestPostTitle";
        $post->summary = "Test post summary";
        $post->content = "Test post content";
        $post->imagePath = "";
        $post->user = $user;

        $result = $postRepository->createPost($post);

        $this->assertTrue($result);
    }


}