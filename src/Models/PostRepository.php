<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
use DateTime;
use PDO;

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
        $post->imagePath = $row['imagePath'];
        $post->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
        $post->publicationDate = $row['publicationDate'] !== null ? $row['publicationDate'] : '';
        $post->lastUpdateDate = $row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '';
        $post->user = $user;
        $post->modifier = $modifier;

        return $post;
    }

    /**
     * Returns an array of post objects
     * If the $pageNumber parameter is set to 0, the function will return all posts
     * If the $pageNumber parameter is different than 0, the function will return the corresponding posts
     * The $numberOfPostsPerPage determins the number of posts to return
     */
    public function getPosts(string $pageNumber, int $numberOfPostsPerPage) : array
    {
        $offset = (($pageNumber - 1) * $numberOfPostsPerPage) >=0 ? (($pageNumber - 1) * $numberOfPostsPerPage) : 0;

        if($pageNumber !== 0 && $numberOfPostsPerPage !== 0){
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM post ORDER BY creationDate DESC LIMIT ". $numberOfPostsPerPage . " OFFSET ". $offset . ";"
            );

            $statement->execute();

        } else { //We return all posts
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM post ORDER BY creationDate DESC;"
            );
    
            $statement->execute();
        }
 
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
            "INSERT INTO post (title, summary, content, creationDate, lastUpdateDate, imagePath, userId) 
            VALUES (?, ?, ?, now(), now(), ?);"
        );

        $affectedLines = $statement->execute([htmlspecialchars($post->title), htmlspecialchars($post->summary), 
        htmlspecialchars($post->content), $post->imagePath, $post->user->id]);

        return ($affectedLines > 0);
    }

    //Update a post
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = ?, summary = ?, content = ?, imagePath = ?, userIdModifier = ?, lastUpdateDate = now() WHERE postId=?;"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content, $post->imagePath, $post->modifier->id, $post->id]);

        return ($affectedLines > 0);
    }

    //Deletes a post
    public function deletePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE postId = ?"
        );

        $affectedLines = $statement->execute([$post->id]);

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

    /**
     * Get the number of records by page
     * The $numberOfPostsPerPage parameter contains the number of posts per page
     */
    public function getTotalPageNumber(int $numberOfPostsPerPage) : int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(postId) AS TotalPosts FROM post;"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalPosts'] / $numberOfPostsPerPage, 2));
    }

    public function searchPosts(string $searchString)
    {
        $searchString = htmlspecialchars($searchString); //Escape special characters

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post WHERE title LIKE '%' :searchString '%' 
            OR summary LIKE '%' :searchString '%' 
            OR content LIKE '%' :searchString '%' 
            ORDER BY creationDate DESC;"

        );

        $statement->bindValue(':searchString', $searchString, PDO::PARAM_STR);

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
}