<?php

namespace Application\Controllers;

use Application\Models\User;

class Connexion 
{
    public function execute() 
    {
        if (isset($_POST['login']) && isset($_POST['password'])){
            if($_POST['login'] !== "" && $_POST['password'] !== ""){
                //Get the user
                $userRepository = new UserRepository();
                $user = $userRepository.getUserByMail($_POST['login']);

            }
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render('connexion.twig', [ 
            
        ]);
    }
}