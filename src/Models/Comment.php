<?php

namespace Application\Models;


class Comment
{
    public int $id;
    public DateTime $publicationDate;
    public string $comment;
    public User $user;
    public Post $post;

    function __construct(int $id, DateTime $publicationDate, string $comment, User $user, Post $post)
    {
        $this->id =  $id;
        $this->publicationDate = $publicationDate;
        $this->comment = $comment;
        $this->user = $user;
        $this->post = $post;
    }
}
