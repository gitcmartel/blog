<?php

namespace Application\Lib;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    #region Properties
    public string $surname;
    public string $name;
    public string $from;
    public string $to;
    public string $subject;
    public string $message;
    public string $errorInfo;

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
        $mail->Host = $parameters->host;
        $mail->Port = $parameters->port;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $parameters->secureMode;
        $mail->Username = $parameters->userName;
        $mail->Password = $parameters->password;
        $mail->setFrom($parameters->mailTo, $author);
        $mail->addReplyTo($this->from, $author);
        $mail->addAddress($this->to, "Contact Devcm");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->subject;
        $mail->Body = $this->message;
        if ($mail->send()){
            return true;
        } else {
            $this->errorInfo = $mail->ErrorInfo;
            return false;
        }
    }
    #endregion
}