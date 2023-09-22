<?php

namespace Application\Models;


class Comment
{
    public int $id;
    public string $creationDate;
    public string $publicationDate;
    public string $comment;
    public User $user;
    public Post $post;

}
