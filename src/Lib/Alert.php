<?php

namespace Application\Lib;

class Alert 
{
    private static array $alerts = [
        'Connexion' => 'Connection réussie !',
        'Disconnexion' => 'Déconnection réussie !',
        'Deletion' => 'Suppression effectuée !', 
        'MailSent' => 'Message envoyé !'
    ];


    #region Functions

    static public function getMessage(string $messageType) : string
    {
        return self::$alerts[$messageType];
    }
}