<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

class PostRepository
{
    private DatabaseConnexion $connexion;

    function __construct()
    {
        $this->$connexion = new DatabaseConnexion();
    }

    //Returns a Post
    public function getPost($postId) : Post
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post WHERE postId = ?;"
        );

        $statement->execute([$postId]);

        $row = $statement->fetch();

        $userRepository = new UserRepository();
        $user = $userRepository->getUser($row['userId']);

        $post = new Post(
            $row['postId'], 
            $row['title'], 
            $row['summary'], 
            $row['content'], 
            $row['creationDate'], 
            $row['publicationDate'], 
            $row['lastUpdateDate'], 
            $user
        );

        return $post;
    }

    //Returns all Posts
    public function getPosts() : array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post ORDER BY creationDate DESC;"
        );

        $statement->execute();
        
        $posts = array();

        while($row = $statement->fetch()) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($row['userId']);

            $post = new Post(
                $row['postId'], 
                $row['title'], 
                $row['summary'], 
                $row['content'], 
                $row['creationDate'], 
                $row['publicationDate'], 
                $row['lastUpdateDate'], 
                $user
            );

            $posts[] = $post; 
        }
        return $posts;
    }

    //Create a new post
    public function createPost($post) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, creationDate, publicationDate, lastUpdateDate, userId) 
            VALUES (?, ?, ?, now(), ?, ?, ?);"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content, $post->creationDate, 
        $post->publicationDate, $post->lastUpdateDate, $post->user->userId]);

        return ($affectedLines > 0);
    }

    //Update a post
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET(title = ?, summary = ?, content = ?, lastUpdateDate = now());"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content]);

        return ($affectedLines > 0);
    }

    //Delete a post
    public function deletePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE postId = ?"
        );

        $affectedLines = $statement->execute([$post->postId]);

        return ($affectedLines > 0);
    }
}