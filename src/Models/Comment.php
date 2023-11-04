<?php

namespace Application\Models;


class Comment extends Table
{
    #region properties

    private string $creationDate;
    private ?string $publicationDate;
    private string $comment;
    private User $user;
    private Post $post;
    
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
        $this->creationDate = $creationDate !== null ? $creationDate : '';
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
        $this->publicationDate = $publicationDate !== null ? $publicationDate : '';
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
