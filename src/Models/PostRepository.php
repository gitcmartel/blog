<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
use DateTime;
use PDO;

class PostRepository
{
    #region Properties
    private DatabaseConnexion $connexion;
    #endregion

    #region Constructor
    function __construct(DatabaseConnexion $dbConnexion)
    {
        $this->connexion = $dbConnexion;
    }
    #endregion

    #region Functions
    //Returns a Post
    public function getPost($postId) : Post
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post WHERE postId = ?;"
        );

        $statement->execute([$postId]);

        $row = $statement->fetch();

        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = $userRepository->getUser($row['userId']);

        if($row['userIdModifier'] !== null){
            $modifier = $userRepository->getUser($row['userIdModifier']);
        } else {
            $modifier = new User();
        }

        $post = new Post();
        $post->setId($row['postId']);
        $post->setTitle($row['title']);
        $post->setSummary($row['summary']);
        $post->setContent($row['content']);
        $post->setImagePath($row['imagePath']);
        $post->setCreationDate($row['creationDate'] !== null ? $row['creationDate'] : '');
        $post->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
        $post->setLastUpdateDate($row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '');
        $post->setUser($user);
        $post->setModifier($modifier);

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
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($row['userId']);

            if($row['userIdModifier'] !== null){
                $modifier = $userRepository->getUser($row['userIdModifier']);
            } else {
                $modifier = new User();
            }

            $post = new Post();
            $post->setId($row['postId']);
            $post->setTitle($row['title']);
            $post->setSummary($row['summary']);
            $post->setContent($row['content']);
            $post->setImagePath($row['imagePath']);
            $post->setCreationDate($row['creationDate'] !== null ? $row['creationDate'] : '');
            $post->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
            $post->setLastUpdateDate($row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '');
            $post->setUser($user);
            $post->setModifier($modifier);

            $posts[] = $post; 
        }
        return $posts;
    }

    //Creates a new post and returns the id of the inserted row if there is an image to insert
    public function createPost(Post $post) : bool
    {
        //Inserts the new post
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, imagePath, creationDate, lastUpdateDate, userId) 
            VALUES (?, ?, ?, ?, now(), now(), ?);"
        );

        $affectedLines = $statement->execute([htmlspecialchars($post->getTitle()), htmlspecialchars($post->getSummary()), 
        htmlspecialchars($post->getContent()), $post->getImagePath(), $post->getUser()->getId()]);

        return $affectedLines > 0;
    }

    //Updates a post
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = ?, summary = ?, content = ?, imagePath = ?, userIdModifier = ?, lastUpdateDate = now() WHERE postId=?;"
        );

        $affectedLines = $statement->execute([$post->getTitle(), $post->getSummary(), $post->getContent(), $post->getImagePath(), $post->getModifier()->getId(), $post->getId()]);

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
     * Get the total number of pages for a given number of posts per page
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

    public function searchPosts(string $searchString) : array
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
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($row['userId']);

            if($row['userIdModifier'] !== null){
                $modifier = $userRepository->getUser($row['userIdModifier']);
            } else {
                $modifier = new User();
            }

            $post = new Post();
            $post->setId($row['postId']);
            $post->setTitle($row['title']);
            $post->setSummary($row['summary']);
            $post->setContent($row['content']);
            $post->setCreationDate($row['creationDate'] !== null ? $row['creationDate'] : '');
            $post->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
            $post->setLastUpdateDate($row['lastUpdateDate'] !== null ? $row['lastUpdateDate'] : '');
            $post->setUser($user);
            $post->setModifier($modifier);

            $posts[] = $post; 
        }
        return $posts;
    }

    //Concatenates the imagePath with the id of the row and updates the imagePath field
    public function updateImagePath(Post $post, string $extension) : string
    {
        $postId = 0;
        $date = date('Y-m-d H:i:s');

        //If we create a new post the id might not be known yet
        if($post->getId() !== null){ //If the id is set
            $postId = $post->getId();
            $date = new DateTime($post->getCreationDate());
        } else {
            //Get the id of the last inserted row
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT postId, creationDate FROM post WHERE postId=LAST_INSERT_ID();"
            );

            $statement->execute();

            $row = $statement->fetch();
            $postId = $row['postId'];
            $date = new DateTime($row['creationDate']);
        }

        if ($postId > 0){
            $imagePath = $post->getImagePath() . $postId . $date->format("YmdHis") . $extension;

            //Updates the imagePath field by adding the id of the new row to the image file name
            $statement = $this->connexion->getConnexion()->prepare(
                "UPDATE post SET imagePath=? WHERE postId=?;"
            );

            $affectedLines = $statement->execute([$imagePath, $postId]);

            return $imagePath;
        }
        return "";
    }

    /**
     * Returns an id's array
     * Can be usefull for blog post navigation
     */
    public function getPostIdList() : array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT postId FROM post ORDER BY postId ASC;"
        );

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    #endregion
}