<?php

namespace Application\Models;

use PDO;

class CommentRepository extends Repository 
{
    #region Functions
    /**
     * Returns a comment object
     */
    public function getComment($commentId) : Comment
    {
        if($commentId === null){
            return new Comment();
        }

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM comment WHERE id = :commentId;"
        );

        $comment = new Comment();

        $statement->bindValue("commentId", $commentId, PDO::PARAM_INT);
        $statement->execute();

        while($row = $statement->fetch()) {
            $userRepository = new UserRepository();
            $postRepository = new PostRepository();
            $row['user'] = $userRepository->getUser($row['user']);
            $row['post'] = $postRepository->getPost($row['post']);

            $comment->hydrate($row);
        }

        return $comment;
    }

    /**
     * Returns an array of comment objects
     */
    public function getComments(string $pageNumber, int $numberOfCommentsPerPage) : array
    {
        $offset = (($pageNumber - 1) * $numberOfCommentsPerPage) >=0 ? (($pageNumber - 1) * $numberOfCommentsPerPage) : 0;

        if($pageNumber !== 0 && $numberOfCommentsPerPage !== 0){
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM comment ORDER BY publicationDate, creationDate DESC LIMIT ". $numberOfCommentsPerPage . " OFFSET ". $offset . ";"
            );

            $statement->execute();

        } else { //We return all comments
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM comment ORDER BY publicationDate, creationDate DESC;"
            );
    
            $statement->execute();
        }

        $comments = array();
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();

        while($row = $statement->fetch()) {
            $comment = new Comment();

            $row['user'] = $userRepository->getUser($row['user']);
            $row['post'] = $postRepository->getPost($row['post']);

            $comment->hydrate($row);

            $comments[] = $comment; 
        }
        return $comments;
    }

    /**
     * Returns an array of comment objects for a specific post
     */
    public function getCommentsPost(Post $post) : array 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM comment WHERE postId = :postId ;"
        );

        $statement->bindValue("postId", $post->getId(), PDO::PARAM_INT);
        $statement->execute();

        $comments = array();
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();

        while ($row = $statement->fetch()) {
            $comment = new Comment();

            $row['user'] = $userRepository->getUser($row['user']);
            $row['post'] = $postRepository->getPost($row['post']);

            $comment->hydrate($row);            

            $comments[] = $comment;
        }

        return $comments;
    }

    /**
     * Creates a new comment record in the database
     */
    public function createComment(Comment $comment)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO comment (creationDate, publicationDate, comment, user, post) 
            VALUES (now(), :publicationDate, :comment, :userId, :postId);"
        );

        $statement->bindValue("publicationDate", $comment->getPublicationDate(), PDO::PARAM_STR);
        $statement->bindValue("comment", $comment->getComment(), PDO::PARAM_STR);
        $statement->bindValue("userId", $comment->getUser()->getId(), PDO::PARAM_INT);
        $statement->bindValue("postId", $comment->getPost()->getId(), PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Updates a comment record
     */
    public function updateComment(Comment $comment)
    {   
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET comment = :comment, publicationDate = :publicationDate WHERE id = :commentId;"
        );

        $statement->bindValue("comment", $comment->getComment(), PDO::PARAM_STR);
        $statement->bindValue("publicationDate", $comment->getPublicationDate(), PDO::PARAM_STR);
        $statement->bindValue("commentId", $comment->getId(), PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Deletes a comment record
     */
    public function deleteComment(Comment $comment)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM comment WHERE id = :commentId;"
        );

        $statement->bindValue("commentId", $comment->getId(), PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Get the number of records by page
     * The $numberOfCommentsPerPage parameter contains the number of comments per page
     */
    public function getTotalPageNumber(int $numberOfCommentsPerPage) : int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(id) AS TotalComments FROM comment;"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalComments'] / $numberOfCommentsPerPage, 2));
    }

    /**
     * Set the publicationDate field to now
     */
    public function setPublicationDate(int $commentId)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET publicationDate = now() WHERE id = :commentId;"
        );

        $statement->bindValue("commentId", $commentId, PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Set the publicationDate field to null
     */
    public function setPublicationDateToNull(int $commentId)
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET publicationDate = null WHERE id = :commentId;"
        );

        $statement->bindValue("commentId", $commentId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Returns a list of Comment objects given the searchString parameter
     * If the searchString parameter is found in one of the following fields : 
     * User : Name, surname, pseudo, email
     * Post : title
     * Comment : comment, creationDate
     */
    public function searchComments(string $searchString)
    {
        $searchString = htmlspecialchars($searchString); //Escape special characters

        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT c.comment, c.creationDate, c.id, p.title, u.name, u.surname, u.pseudo, u.email 
            FROM comment c INNER JOIN user u ON c.user = u.id 
            INNER JOIN post p ON c.post = p.id
            WHERE u.name LIKE :searchString  
            OR u.surname LIKE :searchString 
            OR u.pseudo LIKE :searchString 
            OR u.email LIKE :searchString 
            OR p.title LIKE :searchString 
            OR c.comment LIKE :searchString 
            OR c.creationDate LIKE :searchString 
            ORDER BY creationDate DESC;"

        );

        $statement->bindValue(':searchString', '%' . $searchString . '%', PDO::PARAM_STR);

        $statement->execute();

        $comments = array();

        while($row = $statement->fetch()) {
            $comment = $this->getComment($row['id']);
            $comments[] = $comment; 
        }
        return $comments;
    }

    #endregion
}