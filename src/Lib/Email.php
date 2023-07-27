<?php

namespace Application\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use Application\Lib\MailParameters;
use Application\Lib\Xml;

class Email
{
    public string $surname;
    public string $name;
    public string $from;
    public string $subject;
    public string $message;

    function __construct(string $surname, string $name, string $from, string $subject, string $message)
    {
        $this->surname = htmlspecialchars($surname);
        $this->name = htmlspecialchars($name);
        $this->from = htmlspecialchars($from);
        $this->subject = htmlspecialchars($subject);
        $this->message = htmlspecialchars($message);
    }

    //Sends the email
    public function sendMail() : string
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
        $mail->addAddress($parameters->mailTo, "Contact Devcm");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->subject;
        $mail->Body = "Message envoyé depuis le formulaire de contact du Blog Devcm. \r\n" . 
            "Auteur : " . $author . " - " . $this->from . "\r\n" . $this->message;
        if (!$mail->send()) {
            return 'Erreur de Mailer : ' . $mail->ErrorInfo;
        } else {
            return 'Le message a été envoyé.';
        }
    }
}