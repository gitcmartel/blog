<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\Email;

class EmailTest extends TestCase
{
    #region Functions

    /**
     * Test if an email can be sent
     */
    public function testSendMail()
    {
        $surname = "Martel";
        $name = "Christophe";
        $from = "contact@blog.devcm.fr";
        $to = "contact@blog.devcm.fr";
        $subject = "Test d'envoi mail";
        $message = "Message de test d'envoi mail";
        $mail = new Email($surname, $name, $from, $to, $subject, $message);

        $this->assertTrue($mail->sendMail());
    }

    #endregion
}