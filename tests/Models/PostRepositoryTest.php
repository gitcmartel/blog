<?php

class PostRepositoryTest extends TestCase
{
    public function testCreatePost()
    {
        //Preparing test environnement
        $postRepository = new postRepository(new DbConnexionTest);
        
    }
}