<?php

namespace Application\Models;

use Application\Lib\Constants;
use Application\Lib\Image;
use DateTime;
use PDO;

class PostRepository extends Repository
{
    #region Functions
    /**
     * Returns a Post
     * @param int $postId
     * @return Post
     */
    public function getPost(int $postId): Post
    {
        if ($postId === null) {
            return new Post();
        }

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM post WHERE id = :postId;"
        );

        $statement->bindValue("postId", $postId, PDO::PARAM_INT);
        $statement->execute();

        $post = new Post();

        while ($row = $statement->fetch()) {
            $post->hydrate($row);
        }

        return $post;
    }

    /**
     * Returns an array of post objects
     * If the $pageNumber parameter is set to 0, the function will return all posts
     * If the $pageNumber parameter is different than 0, the function will return the corresponding posts
     * The $numberOfPostsPerPage determins the number of posts to return
     * @param string $pageNumber
     * @param int $numberOfPostsPerPage
     * @param bool $publishedPostsOnly
     * @return array An array of Post objects
     */
    public function getPosts(string $pageNumber, int $numberOfPostsPerPage, bool $publishedPostsOnly): array
    {
        $offset = (($pageNumber - 1) * $numberOfPostsPerPage) >= 0 ? (($pageNumber - 1) * $numberOfPostsPerPage) : 0;
        $whereClause = $publishedPostsOnly === true ? 'WHERE publicationDate IS NOT NULL ' : '';

        if ($pageNumber !== 0 && $numberOfPostsPerPage !== 0) {
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM post " . $whereClause . "ORDER BY creationDate DESC LIMIT " . $numberOfPostsPerPage . " OFFSET " . $offset . ";"
            );

            $statement->execute();

        } else { //We return all posts
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM post " . $whereClause . "ORDER BY creationDate DESC;"
            );

            $statement->execute();
        }

        $posts = array();

        while ($row = $statement->fetch()) {
            $post = new Post();

            $post->hydrate($row);

            $posts[] = $post;
        }
        return $posts;
    }

    /**
     * Creates a new post and returns the id of the inserted row if there is an image to insert
     * @param Post
     */
    public function createPost(Post $post) : void
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

    /**
     * Updates a post (except the imagePath field)
     * @param Post
     */
    public function updatePost(Post $post) : void
    {
        $post->setLastUpdateDate(Date("Y-m-d H:i:s"));

        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET title = :title, summary = :summary, content = :content, modifier = :modifier, publicationDate = :publicationDate, 
            lastUpdateDate = :lastUpdateDate WHERE id= :postId;"
        );

        $statement->bindValue("title", htmlspecialchars($post->getTitle()), PDO::PARAM_STR);
        $statement->bindValue("summary", htmlspecialchars($post->getSummary()), PDO::PARAM_STR);
        $statement->bindValue("content", htmlspecialchars($post->getContent()), PDO::PARAM_STR);
        $statement->bindValue("modifier", htmlspecialchars($post->getModifier()->getId()), PDO::PARAM_INT);
        $statement->bindValue("publicationDate", htmlspecialchars($post->getPublicationDate()), PDO::PARAM_STR);
        $statement->bindValue("lastUpdateDate", htmlspecialchars($post->getLastUpdateDate()), PDO::PARAM_STR);
        $statement->bindValue("postId", htmlspecialchars($post->getId()), PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Deletes a post
     */
    public function deletePost(Post $post) : void
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE id = :postId"
        );

        $statement->bindValue("postId", $post->getId(), PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Set the publicationDate field to now
     * @param int $postId
     */
    public function setPublicationDate(int $postId) : void
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET publicationDate = now() WHERE id = :postId;"
        );

        $statement->bindValue("postId", $postId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Set the publicationDate field to null
     * @param int $postId
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
     * @param int $numberOfPostsPerPage
     * @param bool $onlyPublishedPosts
     * @return int 
     */
    public function getTotalPageNumber(int $numberOfPostsPerPage, bool $onlyPublishedPosts): int
    {
        $publishedCondition = $onlyPublishedPosts ? " WHERE publicationDate IS NOT NULL;" : ";";
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(id) AS TotalPosts FROM post" . $publishedCondition
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalPosts'] / $numberOfPostsPerPage, 2));
    }

    /**
     * Returns an array of post objects witch contains the given characters
     * @param string $searchString
     * @return array An array of Post objects
     */
    public function searchPosts(string $searchString): array
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

        while ($row = $statement->fetch()) {
            $post = new Post();

            $post->hydrate($row);

            $posts[] = $post;
        }
        return $posts;
    }

    /**
     * Concatenates the imagePath with the id of the row and updates the imagePath field
     * @param Post $post
     * @param string $pathImage
     */
    public function updateImagePath(Post $post, string $pathImage) : void
    {
        if ($post->getId() === null) {
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
     * @param Post $post
     */
    public function resetImage(Post $post) : void
    {
        Image::deleteImage($post->getImagePath());
        $this->updateImagePath($post, Constants::DEFAULT_IMAGE_POST_PATH);
    }

    /**
     * Deletes physicaly the previous image
     * Move the new image into the image path folder
     * Updates the new image path into the imagePath post table field
     * @param Post $post
     * @param string $tmpImagePath
     * @param string $imageName
     */
    public function updateImage(Post $post, string $tmpImagePath, string $imageName) : void
    {
        Image::deleteImage($post->getImagePath());
        $pathImage = Constants::IMAGE_POST_PATH . Image::createImagePathName(
            $post->getId(),
            $imageName,
            DateTime::createFromFormat('Y-m-d H:i:s', $post->getCreationDate())
        );

        Image::moveTempImageIntoImagePostFolder($tmpImagePath, $pathImage);

        $this->updateImagePath($post, $pathImage);
    }


    /**
     * Returns an id's array
     * Can be usefull for blog post navigation
     * @return array Of post Ids
     */
    public function getPostIdList(): array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT id FROM post ORDER BY id ASC;"
        );

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    #endregion
}