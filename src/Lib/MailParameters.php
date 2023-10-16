<?php

namespace Application\Lib;

class MailParameters 
{
    #region Properties

    public string $userName;
    public string $password;
    public string $host;
    public string $mailTo;
    public string $port;
    public string $secureMode;
    
    #endregion

    #region Functions
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

    #endregion
}