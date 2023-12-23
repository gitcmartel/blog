<?php

namespace Application\Lib;

class MailParameters 
{
    //region Properties

    private string $userName;
    private string $password;
    private string $host;
    private string $mailTo;
    private string $port;
    private string $secureMode;
    
    //endregion

    //region Functions
    
    function __construct() 
    {
        $parameters = Xml::convertToObject(dirname(__FILE__, 3) . DIRECTORY_SEPARATOR ."src" . DIRECTORY_SEPARATOR . "parameters.xml");
        $this->userName = $parameters->mail->username;
        $this->password = $parameters->mail->password;
        $this->host = $parameters->mail->host;
        $this->mailTo = $parameters->mail->mailTo;
        $this->port = $parameters->mail->port;
        $this->secureMode = $parameters->mail->secureMode;
    }

    //endregion

    //region Getters and Setters

    function getUsername() : string 
    {
        return $this->userName;
    }

    function setUsername(string $userName)
    {
        $this->userName = $userName;
    }

    function getPassword() : string 
    {
        return $this->password;
    }

    function setPassword(string $password)
    {
        $this->password = $password;
    }

    function getHost() : string 
    {
        return $this->host;
    }

    function setHost(string $host)
    {
        $this->host = $host;
    }

    function getMailTo() : string 
    {
        return $this->mailTo;
    }

    function setMailTo(string $mailTo)
    {
        $this->mailTo = $mailTo;
    }

    function getPort() : string 
    {
        return $this->port;
    }

    function setPort(string $port)
    {
        $this->port = $port;
    }

    function getSecureMode() : string 
    {
        return $this->secureMode;
    }

    function setSecureMode(string $secureMode)
    {
        $this->secureMode = $secureMode;
    }
    //endregion
}