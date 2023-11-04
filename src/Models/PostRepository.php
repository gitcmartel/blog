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
        //Inserts the new post
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, imagePath, creationDate, lastUpdateDate, user) 
            VALUES (?, ?, ?, ?, now(), now(), ?);"
        );

        $statement->execute([htmlspecialchars($post->getTitle()), htmlspecialchars($post->getSummary()), 
        htmlspecialchars($post->getContent()), $post->getImagePath(), $post->getUser()->getId()]);

        $post->setId($this->connexion->getConnexion()->lastInsertId());
        $post->setCreationDate(Date(DateTimeInterface::ATOM));
    }

    //Updates a post (except the imagePath field)
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = ?, summary = ?, content = ?, modifier = ?, lastUpdateDate = now() WHERE id=?;"
        );

        $affectedLines = $statement->execute([$post->getTitle(), $post->getSummary(), $post->getContent(), $post->getModifier()->getId(), $post->getId()]);

        return ($affectedLines > 0);
    }

    //Deletes a post
    public function deletePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE id = ?"
        );

        $affectedLines = $statement->execute([$post->getId()]);

        return ($affectedLines > 0);
    }

    /**
     * Set the publicationDate field to now
     */
    public function setPublicationDate(int $postId) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = now() WHERE id = ?;"
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
            "UPDATE post SET publicationDate = null WHERE id = ?;"
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
    public function updateImagePath(?int $postId, string $pathImage) : bool
    {
        if ($postId === null){
            return false;
        }

        //Updates the imagePath field by adding the id of the new row to the image file name

        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET imagePath=? WHERE id=?;"
        );

        $affectedLines = $statement->execute([$pathImage, $postId]);

        return ($affectedLines > 0);

    }

    /**
     * Reset an imagePath field and deletes the physical image file
     */
    public function resetImage(int $postId)
    {
        Image::deleteImagePost($this->getImagePath($postId));
        $this->updateImagePath($postId, Constants::DEFAULT_IMAGE_POST_PATH);
    }

    public function checkImage(string $resetImage, string $tmpImagePath, string $imageName, Post $post)
    {
        //If we have to reset the image
        if($resetImage === 'true'){
            $this->resetImage($post->getId());
        }
        
        //If there is a new image
        if($tmpImagePath !== ''){
            Image::deleteImagePost($post->getImagePath());
            $pathImage = Constants::IMAGE_POST_PATH . Image::createImagePathName(
                $post->getId(), 
                $tmpImagePath, 
                $imageName, 
                DateTime::createFromFormat('Y-m-d H:i:s', $post->getCreationDate())
            );

            Image::moveTempImageIntoImagePostFolder($tmpImagePath, $pathImage);

            $this->updateImagePath($post->getId(), $pathImage);
        }
    }


    /**
     * Checks if the stored image corresponds to the default image path
     */
    private function isImageDefault(int $postId) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT imagePath FROM post WHERE imagePath=?;"
        );

        $statement->execute([$postId]);

        $row = $statement->fetch();

        return ($row['imagePath'] === Constants::IMAGE_POST_PATH . Constants::DEFAULT_IMAGE_POST);
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

    /**
     * Get the imagePath field of a post
     */
    public function getImagePath(int $postId) : string
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT imagePath FROM post WHERE id= ?;"
        );

        $statement->execute([$postId]);

        $row = $statement->fetch();

        return $row["imagePath"];
    }

    #endregion
}