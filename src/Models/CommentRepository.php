<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

class CommentRepository
{
    private DatabaseConnexion $connexion;

    function __construct()
    {
        $this->connexion = new DatabaseConnexion();
    }
    
    /**
     * Returns a comment object
     */
    public function getComment($commentId) : Comment
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM comment WHERE commentId = ?;"
        );

        $statement->execute([$commentId]);

        $row = $statement->fetch();
        $userRepository = new UserRepository();
        $user = $userRepository->getUser($row['userId']);
        $postRepository = new PostRepository();
        $post = $postRepository->getPost($row['postId']);

        $comment->commentId = $row['commentId'];
        $comment->creationDate = $row['creationDate'];
        $comment->publicationDate = $row['publicationDate'];
        $comment->comment = $row['comment'];
        $comment->user = $user;
        $comment->post = $post;


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
                "SELECT * FROM comment ORDER BY creationDate DESC LIMIT ". $numberOfCommentsPerPage . " OFFSET ". $offset . ";"
            );

            $statement->execute();

        } else { //We return all comments
            $statement = $this->connexion->getConnexion()->prepare(
                "SELECT * FROM comment ORDER BY creationDate DESC;"
            );
    
            $statement->execute();
        }

        $comments = array();
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();

        while($row = $statement->fetch()) {
            $comment = new Comment();

            $comment->id = $row['commentId'];
            $comment->creationDate = $row['creationDate'] !== null ? $row['creationDate'] : '';
            $comment->publicationDate = $row['publicationDate'] !== null ? $row['publicationDate'] : '';
            $comment->comment = $row['comment'];
            $comment->user = $userRepository->getUser($row['userId']);
            $comment->post = $postRepository->getPost($row['postId']);

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
            $comments[] = new Comment($row['commentId'], $row['publicationDate'], $row['comment'], $user, $post);
        }

        return $comments;
    }

    /**
     * Creates a new comment record in the database
     */
    public function createComment(Comment $comment) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO comment (publicationDate, comment, userId, postId) 
            VALUES (?, ?, ?, ?);"
        );

        $affectedRows = $statement->execute([$comment->publicationDate, htmlspecialchars($comment->comment), $comment->userId, $comment->postId]);

        return($affectedRows > 0);
    }

    /**
     * Modifies a comment record
     */
    public function modifyComment(Comment $comment) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET (comment = ?);"
        );

        $affectedLines = $statement->execute([$comment->comment]);

        return ($affectedLines > 0);
    }

    /**
     * Deletes a comment record
     */
    public function deleteComment(Comment $comment) : bool 
    {
        $statement = $connexion->getConnexion()->prepare(
            "DELETE FROM comment WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([$comment->commentId]);

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
}