<?php
;
require_once('src/models/post.php');
require_once('src/models/user.php');

class Comment
{
    public int $id;
    public DateTime $publicationDate;
    public string $comment;
    public User $user;
    public Post $post;
}