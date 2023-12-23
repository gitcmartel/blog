<?php

namespace Application\Models;

use Application\Lib\Constants;
use Application\Lib\Path;

class Post extends Table
{
    //region Properties

    private string $title;
    private string $summary;
    private string $content;
    private ?string $imagePath;
    private string $creationDate;
    private ?string $publicationDate;
    private ?string $lastUpdateDate;
    private User $user;
    private User $modifier;
    private UserRepository $userRepository;

    //endregion

    //region Constructor

    function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->userRepository = new UserRepository();
    }

    //endregion

    //region Functions
    /**
     * Deletes the image post if there is one
     * @return bool
     */
    function deleteImage(): bool
    {
        //If the image post is not the default image we delete it
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;

        if ($this->imagePath !== Path::fileBuildPath(array("img", Constants::DEFAULT_IMAGE_POST))) {
            if (file_exists($rootPath . $this->imagePath)) {
                return unlink($rootPath . $this->imagePath);
            }
        }
        return false;
    }
    //endregion

    //region Getters and Setters
    /**
     * Getter
     * @return string
     */
    function getTitle(): string
    {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $title
     */
    function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter
     * @return string
     */
    function getSummary(): string
    {
        if (isset($this->summary)) {
            return html_entity_decode($this->summary);
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $summary
     */
    function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Getter
     * @return string
     */
    function getContent(): string
    {
        if (isset($this->content)) {
            return html_entity_decode($this->content);
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $content
     */
    function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * Getter
     * @return string
     */
    function getImagePath(): string
    {
        if (isset($this->imagePath)) {
            return $this->imagePath;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $imagePath
     */
    function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Getter
     * @return string
     */
    function getCreationDate(): string
    {
        if (isset($this->creationDate)) {
            return $this->creationDate;
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string
     */
    function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Getter
     * @return string or null
     */
    function getPublicationDate(): ?string
    {
        if (isset($this->publicationDate)) {
            return $this->publicationDate;
        } else {
            return null;
        }
    }

    /**
     * Setter
     * @param ?string $publicationDate
     */
    function setPublicationDate(?string $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * Getter
     * @return string
     */
    function getLastUpdateDate(): string
    {
        if (isset($this->lastUpdateDate)) {
            return $this->lastUpdateDate;
        } else {
            return '';
        }
    }

    /**Setter
     * @param string $lastUpdateDate
     */
    function setLastUpdateDate(string $lastUpdateDate): void
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }

    /**Getter
     * @return ?User
     */
    function getUser(): ?User
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            return null;
        }
    }

    /**Setter
     * @param User or int $user
     */
    function setUser($user): void
    {
        $this->user = $user instanceof User ? $user : $this->userRepository->getUser($user);
    }

    function getModifier(): ?User
    {
        if (isset($this->modifier)) {
            return $this->modifier;
        } else {
            return null;
        }
    }

    /**
     * Setter
     * @param User or int
     */
    function setModifier($modifier): void
    {
        $this->modifier = $modifier instanceof User ? $modifier : $this->userRepository->getUser($modifier);
    }
    //endregion
}
