<?php

namespace Application\Lib;

class Email
{
    public string $surname;
    public string $name;
    public string $to;
    public string $from;
    public string $subject;
    public string $message;

    function __construct(string $surname, string $name, string $to, string $from, string $subject, string $message)
    {
        $this->surname = htmlspecialchars($surname);
        $this->name = htmlspecialchars($name);
        $this->to = htmlspecialchars($to);
        $this->from = htmlspecialchars($from);
        $this->subject = htmlspecialchars($subject);
        $this->message = htmlspecialchars($message);
    }

    //Sends the email
    public function sendMail() : bool
    {
        //if the message length is > 70 characters we have to cut it into several lines
        $author = "Envoyé par : " . $this->name . " " . $this->surname . "\r\n";
        $message = wordwrap($this->message, 70, "\r\n");

        $message = $author . $message;
        
        $headers = 'From: ' . $this->from . "\r\n" . 
        'Reply-To: ' . $this->to . "\r\n" . 
        'X-Mailer: PHP/' . phpversion();

        return mail($this->to, $this->subject, $message, $headers);
    }


}