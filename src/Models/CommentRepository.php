<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

class CommentRepository
{
    private DatabaseConnexion $connexion;

    function __construct()
    {
        $this->$connexion = new DatabaseConnexion();
    }
    
    public function getComment($commentId) : getComment
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

        $comment = new Comment($row['commentId'], $row['publicationDate'], $row['comment'], $user, $post);

        return $comment;
    }

    public function getComments() : array
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "SELECT * FROM comment ORDER BY publicationDate DESC;"
        );

        $statement->execute();

        $comments = array();
        $userRepository = new UserRepository();
        $postRepository = new PostRepository();

        while($row = $statement->fetch()) {
            $user = $userRepository($row['userId']);
            $post = $postRepository($row['postId']);
            $comments[] = new Comment($row['commentId'], $row['publicationDate'], $row['comment'], $user, $post);
        }

        return $comments;
    }

    public function getCommentsPost(int $postId) : Post 
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

    public function createComment(Comment $comment) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO comment (publicationDate, comment, userId, postId) 
            VALUES (?, ?, ?, ?);"
        );

        $affectedRows = $statement->execute([$comment->publicationDate, htmlspecialchars($comment->comment), $comment->userId, $comment->postId]);

        return($affectedRows > 0);
    }

    public function modifyComment(Comment $comment) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE comment SET (comment = ?);"
        );

        $affectedLines = $statement->execute([$comment->comment]);

        return ($affectedLines > 0);
    }

    public function deleteComment(Comment $comment) : bool 
    {
        $statement = $connexion->getConnexion()->prepare(
            "DELETE FROM comment WHERE commentId = ?;"
        );

        $affectedLines = $statement->execute([$comment->commentId]);

        return ($affectedLines > 0);
    }
}