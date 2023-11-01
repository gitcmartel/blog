<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;
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
            "SELECT * FROM comment WHERE commentId = ?;"
        );

        $comment = new Comment();

        $statement->execute([$commentId]);

        while($row = $statement->fetch()) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($row['userId']);
            $postRepository = new PostRepository();
            $post = $postRepository->getPost($row['postId']);

            
            $comment->setId($row['commentId']);
            $comment->setCreationDate($row['creationDate'] !== null ? $row['creationDate'] : '');
            $comment->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
            $comment->setComment($row['comment']);
            $comment->setUser($user);
            $comment->setPost($post);
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

            $comment->setId($row['commentId']);
            $comment->setCreationDate($row['creationDate'] !== null ? $row['creationDate'] : '');
            $comment->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
            $comment->setComment($row['comment']);
            $comment->setUser($userRepository->getUser($row['userId']));
            $comment->setPost($postRepository->getPost($row['postId']));

            $comments[] = $comment; 
        }
        return $comments;
    }

    /**
     * Returns an array of comment objects for a specific post
     */
    public function getCommentsPost(int $postId) : array 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM comment WHERE postId = ? ;"
        );

        $statement->execute([$postId]);

        $comments = array();
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();
        while ($row = $statement->fetch()) {
            $user = $userRepository->getUser($row['userId']);
            $post = $postRepository->getPost($row['postId']);
            $comment = new Comment();
            $comment->setId($row['commentId']);
            $comment->setPublicationDate($row['publicationDate'] !== null ? $row['publicationDate'] : '');
            $comment->setComment($row['comment']);
            $comment->setUser($userRepository->getUser($row['userId']));
            $comment->setPost($postRepository->getPost($row['postId']));
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
            "INSERT INTO comment (creationDate, publicationDate, comment, userId, postId) 
            VALUES (now(), ?, ?, ?, ?);"
        );

        $affectedRows = $statement->execute([
            $comment->getPublicationDate(), 
            htmlspecialchars($comment->getComment()), 
            $comment->getUser()->getId(), 
            $comment->getPost()->getId()]);
    }

    /**
     * Updates a comment record
     */
    public function updateComment(string $comment, ?string $publicationDate, int $commentId) : bool 
    {
        $publicationDate = self::checkPublicationDate($commentId, $publicationDate);
        
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET comment = ?, publicationDate = ? WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([
            htmlspecialchars($comment), 
            $publicationDate, 
            $commentId]);

        return ($affectedLines > 0);
    }

    /**
     * Checks if there is a publicationDate
     * If yes we return the publicationDate that is actually stored in the database
     * If no we return the publicationDate function parameter
     */
    private function checkPublicationDate(int $commentId, ?string $publicationDate) : ?string
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT publicationDate FROM comment WHERE commentId = ? ;"
        );

        $statement->execute([$commentId]);

        $row = $statement->fetch();

        if($row['publicationDate'] !== null && $publicationDate !== null){
            return $row['publicationDate'];
        } else {
            return $publicationDate;
        }
    }

    /**
     * Deletes a comment record
     */
    public function deleteComment(Comment $comment) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM comment WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([$comment->getId()]);

        return ($affectedLines > 0);
    }

    /**
     * Get the number of records by page
     * The $numberOfCommentsPerPage parameter contains the number of comments per page
     */
    public function getTotalPageNumber(int $numberOfCommentsPerPage) : int
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT COUNT(commentId) AS TotalComments FROM comment;"
        );

        $statement->execute();

        $row = $statement->fetch();

        return ceil(round($row['TotalComments'] / $numberOfCommentsPerPage, 2));
    }

    /**
     * Set the publicationDate field to now
     */
    public function setPublicationDate(int $commentId) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET publicationDate = now() WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([$commentId]);

        return ($affectedLines > 0);
    }

    /**
     * Set the publicationDate field to null
     */
    public function setPublicationDateToNull(int $commentId) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET publicationDate = null WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([$commentId]);

        return ($affectedLines > 0);
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
            "SELECT c.comment, c.creationDate, c.commentId, p.title, u.name, u.surname, u.pseudo, u.email 
            FROM comment c INNER JOIN user u ON c.userId = u.userId 
            INNER JOIN post p ON c.postId = p.postId
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
            $comment = $this->getComment($row['commentId']);
            $comments[] = $comment; 
        }
        return $comments;
    }

    #endregion
}