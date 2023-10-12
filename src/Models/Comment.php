<?php

namespace Application\Models;


class Comment
{
    #region properties
    private int $id;
    private string $creationDate;
    private $publicationDate; //No type defined because we want this property to be null when a comment is not valid
    private string $comment;
    private User $user;
    private Post $post;
    #endregion

    #region getters and setters
    function getId() : int
    {
        if(isset($this->id)){
            return $this->id;
        } else {
            return 0;
        }  
    }

    function setId(int $id)
    {
        $this->id = $id;
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

    function getPublicationDate()
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

    function getPost() : Post 
    {
        if (isset($this->post)){
            return $this->post;
        } else {
            return null;
        }
    }

    function setPost(Post $post)
    {
        $this->post = $post;
    }
    #endregion
}
