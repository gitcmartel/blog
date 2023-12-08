<?php

namespace Application\Models;


class Comment extends Table
{
    #region properties

    private string $creationDate;
    private ?string $publicationDate;
    private string $comment;
    private bool $isValid;
    private User $user;
    private Post $post;
    private UserRepository $userRepository;
    private PostRepository $postRepository;
    
    #endregion

    #region Constructor
    function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->userRepository = new UserRepository();
        $this->postRepository = new PostRepository();
    }

    #endregion

    #region getters and setters

    function getCreationDate() : string 
    {
        if(isset($this->creationDate)){
            return $this->creationDate;
        } else {
            return '';
        }
    }

    function setCreationDate(?string $creationDate)
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

    function setPublicationDate(?string $publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    function getComment() : string 
    {
        if(isset($this->comment)){
            return $this->comment;
        } else {
            return '';
        }
    }

    function setComment(string $comment) 
    {
        $this->comment = $comment;
    }

    function getUser() : ?User 
    {
        if (isset($this->user)){
            return $this->user;
        } else {
            return null;
        }
    }

    function getIsValid() : bool 
    {
        if (isset($this->isValid)){
            return $this->isValid;
        } else {
            return false;
        }
    }

    function setIsValid(bool $isValid) 
    {
        $this->isValid = $isValid;
    }
    
    function setUser($user)
    {
        $this->user = $user instanceof User ? $user : $this->userRepository->getUser($user);
    }

    function getPost() : ?Post 
    {
        if (isset($this->post)){
            return $this->post;
        } else {
            return null;
        }
    }

    function setPost($post)
    {
        $this->post = $post instanceof Post ? $post : $this->postRepository->getPost($post);
    }
    #endregion
}
