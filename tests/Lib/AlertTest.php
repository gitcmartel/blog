<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\Alert;


class AlertTest extends TestCase
{
    public function testGetMessageReturnsCorrectMessageForConnexion()
    {
        $this->assertEquals('Connection réussie !', Alert::getMessage('Connexion'));
    }

    public function testGetMessageReturnsCorrectMessageForDisconnexion()
    {
        $this->assertEquals('Déconnection réussie !', Alert::getMessage('Disconnexion'));
    }

    public function testGetMessageReturnsCorrectMessageForDeletion()
    {
        $this->assertEquals('Suppression effectuée !', Alert::getMessage('Deletion'));
    }

    public function testGetMessageReturnsCorrectMessageForMailSent()
    {
        $this->assertEquals('Message envoyé !', Alert::getMessage('MailSent'));
    }

    public function testGetMessageReturnsDefaultMessageForUnknownType()
    {
        $this->assertEquals('Type de message inconnu !', Alert::getMessage('UnknownMessage'));
    }
}