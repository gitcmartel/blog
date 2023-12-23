<?php

namespace Application\Models;


class Comment extends Table
{
    //region properties

    private string $creationDate;
    private ?string $publicationDate;
    private string $comment;
    private bool $isValid;
    private User $user;
    private Post $post;
    private UserRepository $userRepository;
    private PostRepository $postRepository;

    //endregion

    //region Constructor
    function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->userRepository = new UserRepository();
        $this->postRepository = new PostRepository();
    }

    //endregion

    //region getters and setters
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
     * @param string $creationDate
     */
    function setCreationDate(?string $creationDate): void
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
     * @param string $publicationDate
     */
    function setPublicationDate(?string $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * Getter
     * @return string
     */
    function getComment(): string
    {
        if (isset($this->comment)) {
            return html_entity_decode($this->comment);
        } else {
            return '';
        }
    }

    /**
     * Setter
     * @param string $comment
     */
    function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter
     * @return User or null
     */
    function getUser(): ?User
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            return null;
        }
    }

    /**
     * Getter
     * @return bool
     */
    function getIsValid(): bool
    {
        if (isset($this->isValid)) {
            return $this->isValid;
        } else {
            return false;
        }
    }

    /**
     * Setter
     * @param bool $isValid
     */
    function setIsValid(bool $isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * Setter
     * @param User $user or int
     */
    function setUser($user): void
    {
        $this->user = $user instanceof User ? $user : $this->userRepository->getUser($user);
    }

    /**
     * Getter
     * @return Post or null
     */
    function getPost(): ?Post
    {
        if (isset($this->post)) {
            return $this->post;
        } else {
            return null;
        }
    }

    /**
     * Setter
     * @param Post $post or int
     */
    function setPost($post): void
    {
        $this->post = $post instanceof Post ? $post : $this->postRepository->getPost($post);
    }
    //endregion
}
