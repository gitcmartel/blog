<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUserSearch
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";

        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isCreator()  && $activeUser->isValid){
                if(isset($_POST['searchString'])){
                    if(trim($_POST['searchString']) !== ""){
                        $users = $userRepository->searchUsers(trim($_POST['searchString']));

                        $loader = new \Twig\Loader\FilesystemLoader('templates');
                        $twig = new \Twig\Environment($loader, ['cache' => false]);
                        
                        echo $twig->render('adminUserList.twig', [ 
                            'actualPage' => "1", 
                            'totalPages' => "1", 
                            'users' => $users, 
                            'userFunction' => $userFunction,
                            'activeUser' => Session::getActiveUser()
                        ]);
                        return;
                    }
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
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);    
    }
}