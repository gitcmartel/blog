<?php

namespace Application\Lib;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    #region Properties
    private string $surname = '';
    private string $name = '';
    private string $from = '';
    private string $to = '';
    private string $subject = '';
    private string $message = '';
    private string $errorInfo = '';

    #endregion

    #region Functions

    function __construct(string $surname, string $name, string $from, string $to, string $subject, string $message)
    {
        $this->surname = htmlspecialchars($surname);
        $this->name = htmlspecialchars($name);
        $this->from = htmlspecialchars($from);
        $this->to =  htmlspecialchars($to);
        $this->subject = htmlspecialchars($subject);
        $this->message = $message;
    }

    //Sends the email
    public function sendMail() : bool
    {
        //Get the parameters
        $parameters = new MailParameters();

        //if the message length is > 70 characters we have to cut it into several lines
        $author = $this->name . " " . $this->surname;
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = $parameters->getHost();
        $mail->Port = $parameters->getPort();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $parameters->getSecureMode();
        $mail->Username = $parameters->getUserName();
        $mail->Password = $parameters->getPassword();
        $mail->setFrom($parameters->getMailTo(), $author);
        $mail->addReplyTo($this->from, $author);
        $mail->addAddress($this->to, "Contact Devcm");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->subject;
        $mail->Body = $this->message;
        if ($mail->send()){
            return true;
        } else {
            $this->errorInfo = $mail->getErrorInfo();
            return false;
        }
    }

    public static function checkMailFormat(string $email) : bool
    {
        $emailRegExp = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
        if (! (preg_match($emailRegExp, trim($email)) === 1)){
            return false;
        }
        return true;
    }
    
    #endregion

    #region Getters and setters

    function getSurname() : string 
    {
        return $this->surname;
    }

    function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    function getName() : string 
    {
        return $this->name;
    }

    function setName(string $name)
    {
        $this->name = $name;
    }

    function getFrom() : string 
    {
        return $this->from;
    }

    function setFrom(string $from)
    {
        $this->from = $from;
    }

    function getTo() : string 
    {
        return $this->to;
    }

    function setTo(string $to)
    {
        $this->to = $to;
    }

    function getSubject() : string 
    {
        return $this->subject;
    }

    function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    function getMessage() : string 
    {
        return $this->message;
    }

    function setMessage(string $message)
    {
        $this->message = $message;
    }

    function getErrorInfo() : string 
    {
        return $this->errorInfo;
    }

    function setErrorInfo(string $errorInfo)
    {
        $this->errorInfo = $errorInfo;
    }
    #endregion
}