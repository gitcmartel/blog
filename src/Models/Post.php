<?php

namespace Application\Models;

use Application\Lib\Constants;
use Application\Lib\Path;

class Post extends Table
{
    #region Properties

    private string $title;
    private string $summary;
    private string $content;
    private ?string $imagePath;
    private string $creationDate;
    private ?string $publicationDate;
    private string $lastUpdateDate;
    private User $user;
    private User $modifier;

    #endregion

    #region Functions
    /**
     * Deletes the image post if there is one
     */
    function deleteImage() : bool
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
    #endregion

    #region Getters and Setters

    function getTitle() : string 
    {
        if(isset($this->title)){
            return $this->title;
        } else {
            return '';
        }
    }

    function setTitle(string $title)
    {
        $this->title = $title;
    }

    function getSummary() : string 
    {
        if(isset($this->summary)){
            return $this->summary;
        } else {
            return '';
        }
    }

    function setSummary(string $summary)
    {
        $this->summary = $summary;
    }

    function getContent() : string 
    {
        if(isset($this->content)){
            return $this->content;
        } else {
            return '';
        }
    }

    function setContent(string $content)
    {
        $this->content = $content;
    }

    function getImagePath() : string 
    {
        if(isset($this->imagePath)){
            return $this->imagePath;
        } else {
            return '';
        }
    }

    function setImagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    function getCreationDate() : string
    {
        if(isset($this->creationDate)){
            return $this->creationDate;
        } else {
            return '';
        }
    }

    function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    function getPublicationDate() : ?string
    {
        if(isset($this->publicationDate)){
            return $this->publicationDate;
        } else {
            return null;
        }
    }

    function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    function getLastUpdateDate() : string
    {
        if(isset($this->lastUpdateDate)){
            return $this->lastUpdateDate;
        } else {
            return '';
        }
    }

    function setLastUpdateDate(string $lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }

    function getUser() : User 
    {
        if (isset($this->user)){
            return $this->user;
        } else {
            return null;
        }
    }

    function setUser(User $user)
    {
        $this->user = $user;
    }

    function getModifier() : User 
    {
        if (isset($this->modifier)){
            return $this->modifier;
        } else {
            return null;
        }
    }

    function setModifier(User $modifier)
    {
        $this->modifier = $modifier;
    }
    #endregion
}
