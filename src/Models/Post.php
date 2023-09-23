<?php

namespace Application\Models;

use Application\Lib\Constants;
use Application\Lib\Path;

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

    /**
     * Deletes the image post if there is one
     */
    public function deleteImage() : bool
    {
        //If the image post is not the default image we delete it
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;

        if ($this->imagePath !== Path::fileBuildPath(array("img", Constants::DEFAULT_IMAGE_POST))){
            if (file_exists($rootPath . $this->imagePath)){
                return unlink($rootPath . $this->imagePath);
            }
        }
        return false;
    }
}
