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
}

