<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUser
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            if($activeUser->isCreator()){
                if (isset($_GET['userId'])){
                    $user = $userRepository->getUser($_GET['userId']);

                    echo $twig->render('user.twig', [ 
                        'user' => $user, 
                        'activeUser' => Session::getActiveUser(), 
                        'userFunction' => $userFunction
                    ]);
                }
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('notAllowed.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}