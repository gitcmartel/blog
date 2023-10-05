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
        $post->imagePath = "img\\img-posts\\";
        $post->user = $user;

        $result = $postRepository->createPost($post);

        //Executing test
        $this->assertTrue($result);
    }

    public function testGetPost()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);

        //Fetch a post object
        $post = $postRepository->getPost(1);

        //Executing test
        $this->assertInstanceOf("Application\\Models\\Post", $post);
    }

    public function testGetPosts()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);

        $posts = $postRepository->getPosts(0, 0);

        //Executing test
        $this->assertInstanceOf("Application\\Models\\Post", $posts[0]);
    }

    public function testUpdatePost()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);

        //Creates a user object and set the id to 1 (the user id that has been previously created)
        $user = new User();
        $user->id = 1;

        $post = new Post();
        $post->id = 1;
        $post->title = 'TestPostTitleUpdate';
        $post->summary = 'Test post summary update';
        $post->content = 'Test post content update';
        $post->imagePath = "img\\img-posts\\";
        $post->modifier = $user;

        $result = $postRepository->updatePost($post);

        //Executing test
        $this->assertTrue($result);
    }


    public function testSetPublicationDate()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);

        //Test the function with the post id set to 1 (previously created)
        $result = $postRepository->setPublicationDate(1);

        //Executing test
        $this->assertTrue($result);
    }

    public function testSetPublicationDateToNull()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);

        //Test the function with the post id set to 1 (previously created)
        $result = $postRepository->setPublicationDateToNull(1);

        //Executing test
        $this->assertTrue($result);
    }

    public function testGetTotalPageNumber()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 
        
        //Set a user object
        $user = new User();
        $user->id = 1;

        //Creates 2 more posts
        $post = new Post();
        $post->title = "TestPostTitle1";
        $post->summary = "Test post summary1";
        $post->content = "Test post content1";
        $post->imagePath = "img\\img-posts\\";
        $post->user = $user;

        $postRepository->createPost($post);

        $post->title = "TestPostTitle2";
        $post->summary = "Test post summary2";
        $post->content = "Test post content2";
        $post->imagePath = "img\\img-posts\\";

        $postRepository->createPost($post);

        //Fetch the total number of pages for 3 posts per page
        //Given that there is only 3 posts created the result should be 1
        $result = $postRepository->getTotalPageNumber(3);

        //Executing test
        $this->assertEquals(1, $result);
    }

    public function testSearchPostByTitleTrue()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 

        $posts = $postRepository->searchPosts("TestPostTitle1");

        //Executing test
        $this->assertInstanceOf("Application\Models\Post", $posts[0]);
    }

    public function testSearchPostBySummaryTrue()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 

        $posts = $postRepository->searchPosts("Test post summary1");

        //Executing test
        $this->assertInstanceOf("Application\Models\Post", $posts[0]);
    }

    public function testSearchPostByContentTrue()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 

        $posts = $postRepository->searchPosts("Test post content2");

        //Executing test
        $this->assertInstanceOf("Application\Models\Post", $posts[0]);
    }

    public function testUpdateImagePathTrue()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 

        $post = $postRepository->getPost(2);

        $result = $postRepository->updateImagePath($post, ".jpg");

        //Executing test
        $this->assertNotEquals("", $result);
    }

    public function testGetPostIdList()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest); 
        
        $result = $postRepository->getPostIdList();

        //Executing test
        $this->assertTrue(count($result) > 0);
    }

    public function testDeletePost()
    {
        //Preparing test environnement
        $postRepository = new PostRepository(new DbConnexionTest);
        
        $post = new Post();
        $post->id = 1;

        $result = $postRepository->deletePost($post);

        //Executing test
        $this->assertTrue($result);
    }
}