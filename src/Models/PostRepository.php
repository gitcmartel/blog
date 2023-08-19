<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
use DateTime;

class PostRepository
{
    private DatabaseConnexion $connexion;

    function __construct()
    {
        $this->connexion = new DatabaseConnexion();
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

        if($row['userIdModifier'] !== null){
            $modifier = $userRepository->getUser($row['userIdModifier']);
        } else {
            $modifier = new User();
        }

        $post = new Post();
        $post->id = $row['postId'];
        $post->title = $row['title'];
        $post->summary = $row['summary'];
        $post->content = $row['content'];
        $post->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
        $post->publicationDate = $row['publicationDate'] !== null ? $row['publicationDate'] : '';
        $post->lastUpdateDate = $row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '';
        $post->user = $user;
        $post->modifier = $modifier;

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

            if($row['userIdModifier'] !== null){
                $modifier = $userRepository->getUser($row['userIdModifier']);
            } else {
                $modifier = new User();
            }

            $post = new Post();
            $post->id = $row['postId'];
            $post->title = $row['title'];
            $post->summary = $row['summary'];
            $post->content = $row['content'];
            $post->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
            $post->publicationDate = $row['publicationDate'] !== null ? $row['publicationDate'] : '';
            $post->lastUpdateDate = $row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '';
            $post->user = $user;
            $post->modifier = $modifier;

            $posts[] = $post; 
        }
        return $posts;
    }

    //Create a new post
    public function createPost($post) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, creationDate, lastUpdateDate, userId) 
            VALUES (?, ?, ?, now(), now(), ?);"
        );

        $affectedLines = $statement->execute([htmlspecialchars($post->title), htmlspecialchars($post->summary), 
        htmlspecialchars($post->content), $post->user->id]);

        return ($affectedLines > 0);
    }

    //Update a post
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = ?, summary = ?, content = ?, lastUpdateDate = now() WHERE postId=?;"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content, $post->id]);

        return ($affectedLines > 0);
    }

    //Deletes a post
    public function deletePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE postId = ?"
        );

        $affectedLines = $statement->execute([$post->postId]);

        return ($affectedLines > 0);
    }

    /**
     * Set the publicationDate field to now
     */
    public function setPublicationDate(int $postId) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = now() WHERE postId = ?;"
        );

        $affectedLines = $statement->execute([$postId]);

        return ($affectedLines > 0);
    }

    /**
     * Set the publicationDate field to null
     */
    public function setPublicationDateToNull(int $postId) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = null WHERE postId = ?;"
        );

        $affectedLines = $statement->execute([$postId]);

        return ($affectedLines > 0);
    }
}