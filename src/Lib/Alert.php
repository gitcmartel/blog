<?php

namespace Application\Lib;

class Alert 
{
    private static array $alerts = [
        'Connexion' => 'Connection réussie !',
        'Disconnexion' => 'Déconnection réussie !',
        'Deletion' => 'Suppression effectuée !', 
        'MailSent' => 'Message envoyé !', 
        'PasswordChange' => 'Mot de passe modifié !'
    ];


    #region Functions

    static public function getMessage(string $messageType) : string
    {
        if(!array_key_exists($messageType, self::$alerts)){
            return 'Type de message inconnu !';
        }

        return self::$alerts[$messageType];
    }
}