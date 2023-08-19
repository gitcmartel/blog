<?php

namespace Application\Models;

class Post 
{
    public int $id;
    public string $title;
    public string $summary;
    public string $content;
    public string $imagePath;
    public string $creationDate;
    public string $publicationDate;
    public string $lastUpdateDate;
    public User $user;
    public User $modifier;

}
