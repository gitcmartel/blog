<?php

require_once('src/lib/database.php');
require_once('src/models/user.php');

class Post 
{
    public int $id;
    public string $title;
    public string $summary;
    public string $content;
    public DateTime $creationDate;
    public DateTime $publicationDate;
    public DateTime $lastUpdateTime;
    public User $user;

    function __construct(int $id, string $title, string $sumary, string $content, DateTime $creationDate, 
    DateTime $publicationDate, DateTime $lastUpdateTime, User $user)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->$content = $content;
        $this->creationDate = $creationDate;
        $this->publicationDate = $publicationDate;
        $this->lastUpdateTime = $lastUpdateTime;
        $this->user = $user;
    }
}

class PostRepository
{
    private DatabaseConnexion $connexion;

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

        $post = new Post(
            $row['postId'], 
            $row['title'], 
            $row['summary'], 
            $row['content'], 
            $row['creationDate'], 
            $row['publicationDate'], 
            $row['lastUpdateDate'], 
            $user
        );

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

            $post = new Post(
                $row['postId'], 
                $row['title'], 
                $row['summary'], 
                $row['content'], 
                $row['creationDate'], 
                $row['publicationDate'], 
                $row['lastUpdateDate'], 
                $user
            );

            $posts[] = $post; 
        }
        return $posts;
    }

    //Create a new post
    public function createPost($post) : bool
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "INSERT INTO post (title, summary, content, creationDate, publicationDate, lastUpdateDate, userId) 
            VALUES (?, ?, ?, now(), ?, ?, ?);"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content, $post->creationDate, 
        $post->publicationDate, $post->lastUpdateDate, $post->user->userId]);

        return ($affectedLines > 0);
    }

    //Update a post
    public function updatePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "UPDATE post SET(title = ?, summary = ?, content = ?, lastUpdateDate = now());"
        );

        $affectedLines = $statement->execute([$post->title, $post->summary, $post->content]);

        return ($affectedLines > 0);
    }

    //Delete a post
    public function deletePost($post) : bool 
    {
        $statement = $this->connexion->getConnexion()->prepare(
            "DELETE FROM post WHERE postId = ?"
        );

        $affectedLines = $statement->execute([$post->postId]);

        return ($affectedLines > 0);
    }
}