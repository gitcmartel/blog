<?php

namespace Application\Models;

use Application\Lib\Constants;
use Application\Lib\Image;
use DateTime;
use DateTimeInterface;
use PDO;

class PostRepository extends Repository
{
    #region Functions
    //Returns a Post
    public function getPost($postId) : Post
    {
        if($postId === null){
            return new Post();
        }
        
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post WHERE id = :postId;"
        );

        $statement->bindValue("postId", $postId, PDO::PARAM_INT);
        $statement->execute();

        $post = new Post();
        $userRepository = new UserRepository();

        while($row = $statement->fetch()) {
            $row['user'] = $userRepository->getUser($row['user']);

            if($row['modifier'] !== null){
                $row['modifier'] = $userRepository->getUser($row['modifier']);
            } else {
                $row['modifier']= new User();
            }
    
            $post->hydrate($row);
        }

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
            $row['user'] = $userRepository->getUser($row['user']);

            if($row['modifier'] !== null){
                $row['modifier'] = $userRepository->getUser($row['modifier']);
            } else {
                $row['modifier'] = new User();
            }

            $post = new Post();

            $post->hydrate($row);

            $posts[] = $post; 
        }
        return $posts;
    }

    //Creates a new post and returns the id of the inserted row if there is an image to insert
    public function createPost(Post $post)
    {
        $post->setCreationDate(Date('Y-m-d H:i:s'));
        $post->setLastUpdateDate($post->getCreationDate());

        //Inserts the new post
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, imagePath, creationDate, lastUpdateDate, user) 
            VALUES (:title, :summary, :content, :imagePath, :creationDate, :lastUpdateDate, :userId);"
        );

        $statement->bindValue("title", htmlspecialchars($post->getTitle()), PDO::PARAM_STR);
        $statement->bindValue("summary", htmlspecialchars($post->getSummary()), PDO::PARAM_STR);
        $statement->bindValue("content", htmlspecialchars($post->getContent()), PDO::PARAM_STR);
        $statement->bindValue("imagePath", htmlspecialchars($post->getImagePath()), PDO::PARAM_STR);
        $statement->bindValue("creationDate", htmlspecialchars($post->getCreationDate()), PDO::PARAM_STR);
        $statement->bindValue("lastUpdateDate", htmlspecialchars($post->getLastUpdateDate()), PDO::PARAM_STR);
        $statement->bindValue("userId", htmlspecialchars($post->getUser()->getId()), PDO::PARAM_INT);

        $statement->execute();

        $post->setId($this->connexion->getConnexion()->lastInsertId());
    }

    //Updates a post (except the imagePath field)
    public function updatePost($post)
    {
        $post->setLastUpdateDate(Date("Y-m-d H:i:s"));

        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = :title, summary = :summary, content = :content, modifier = :modifier, lastUpdateDate = :lastUpdateDate WHERE id= :postId;"
        );

        $statement->bindValue("title", htmlspecialchars($post->getTitle()), PDO::PARAM_STR);
        $statement->bindValue("summary", htmlspecialchars($post->getSummary()), PDO::PARAM_STR);
        $statement->bindValue("content", htmlspecialchars($post->getContent()), PDO::PARAM_STR);
        $statement->bindValue("modifier", htmlspecialchars($post->getModifier()->getId()), PDO::PARAM_INT);
        $statement->bindValue("lastUpdateDate", htmlspecialchars($post->getLastUpdateDate()), PDO::PARAM_STR);
        $statement->bindValue("postId", htmlspecialchars($post->getId()), PDO::PARAM_INT);

        $statement->execute();
    }

    //Deletes a post
    public function deletePost($post)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE id = :postId"
        );

        $statement->bindValue("postId", $post->getId(), PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Set the publicationDate field to now
     */
    public function setPublicationDate(int $postId)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = now() WHERE id = :postId;"
        );

        $statement->bindValue("postId", $postId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Set the publicationDate field to null
     */
    public function setPublicationDateToNull(int $postId)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = null WHERE id = :postId;"
        );

        $statement->bindValue("postId", $postId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Get the total number of pages for a given number of posts per page
     * The $numberOfPostsPerPage parameter contains the number of posts per page
     */
    public function getTotalPageNumber(int $numberOfPostsPerPage) : int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(id) AS TotalPosts FROM post;"
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
            $userRepository = new UserRepository();
            $row['user'] = $userRepository->getUser($row['user']);

            if($row['modifier'] !== null){
                $row['modifier'] = $userRepository->getUser($row['modifier']);
            } else {
                $row['modifier'] = new User();
            }

            $post = new Post();

            $post->hydrate($row);

            $posts[] = $post; 
        }
        return $posts;
    }

    //Concatenates the imagePath with the id of the row and updates the imagePath field
    public function updateImagePath(Post $post, string $pathImage)
    {
        if ($post->getId() === null){
            return false;
        }

        //Updates the imagePath field by adding the id of the new row to the image file name

        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET imagePath= :imagePath WHERE id= :postId;"
        );

        $statement->bindValue("postId", $post->getId(), PDO::PARAM_INT);
        $statement->bindValue("imagePath", $pathImage, PDO::PARAM_STR);

        $statement->execute();
    }

    /**
     * Reset an imagePath field and deletes the physical image file
     */
    public function resetImage(Post $post)
    {
        Image::deleteImage($post->getImagePath());
        $this->updateImagePath($post, Constants::DEFAULT_IMAGE_POST_PATH);
    }

    /**
     * Deletes physicaly the previous image
     * Move the new image into the image path folder
     * Updates the new image path into the imagePath post table field
     */
    public function updateImage(Post $post, string $tmpImagePath, string $imageName)
    {
        Image::deleteImage($post->getImagePath());
        $pathImage = Constants::IMAGE_POST_PATH . Image::createImagePathName(
            $post->getId(), 
            $tmpImagePath, 
            $imageName, 
            DateTime::createFromFormat('Y-m-d H:i:s', $post->getCreationDate())
        );

        Image::moveTempImageIntoImagePostFolder($tmpImagePath, $pathImage);

        $this->updateImagePath($post, $pathImage);
    }
    

    /**
     * Returns an id's array
     * Can be usefull for blog post navigation
     */
    public function getPostIdList() : array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT id FROM post ORDER BY id ASC;"
        );

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    #endregion
}