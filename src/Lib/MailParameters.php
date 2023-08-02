<?php

namespace Application\Lib;

class MailParameters 
{
    public string $userName;
    public string $password;
    public string $host;
    public string $mailTo;
    public string $port;
    public string $secureMode;
    

    function __construct() 
    {
        $parameters = Xml::convertToArray("src/parameters.xml");
        $this->userName = $parameters->mail->username;
        $this->password = $parameters->mail->password;
        $this->host = $parameters->mail->host;
        $this->mailTo = $parameters->mail->mailTo;
        $this->port = $parameters->mail->port;
        $this->secureMode = $parameters->mail->secureMode;
    }
}