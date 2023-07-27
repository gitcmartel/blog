<?php

namespace Application\Models;


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
